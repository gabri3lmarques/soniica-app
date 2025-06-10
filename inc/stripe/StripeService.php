<?php

namespace stripe;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Subscription;
use Stripe\Event;

class StripeService
{
    private $priceId;
    private const CACHE_DURATION = HOUR_IN_SECONDS * 12; // 12 horas

    public function __construct($priceId = 'price_1RY95U2KcSHWYSnHWE0Cr6pD')
    {
        Stripe::setApiKey(STRIPE_SECRET_KEY);
        $this->priceId = $priceId;
    }

    /**
     * Cria uma sessão de checkout para assinatura premium.
     */
    public function createCheckoutSession($userEmail)
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'], 
                'mode' => 'subscription',
                'customer_email' => $userEmail,
                'line_items' => [[
                    'price' => $this->priceId,
                    'quantity' => 1,
                ]],
                'success_url' => home_url('/premium-success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => home_url('/get-premium'),
                'metadata' => [
                    'user_email' => $userEmail
                ]
            ]);
    
            return $session->url;
        } catch (\Exception $e) {
            $this->logError('Erro ao criar a sessão de checkout', $e);
            return false;
        }
    }

    /**
     * Verifica se o usuário é premium.
     * 
     * @param int $userId ID do usuário no WordPress
     * @return bool Verdadeiro se o usuário for premium, falso caso contrário.
     */
    public function isUserPremium($userId)
    {
        // Verifica cache primeiro
        $cached_status = get_transient('user_premium_status_' . $userId);
        if ($cached_status !== false) {
            return (bool) $cached_status;
        }

        $user = get_user_by('id', $userId);
        if (!$user) {
            return false;
        }

        try {
            $status = $this->checkStripeSubscription($user->user_email);
            $this->cacheUserPremiumStatus($userId, $status);
            return $status;
        } catch (\Exception $e) {
            $this->logError('Erro ao verificar status premium', $e);
            return false;
        }
    }

    public function handleWebhook($payload)
    {
        try {
            $event = Event::constructFrom($payload);
            
            switch ($event->type) {
                case 'customer.subscription.created':
                case 'customer.subscription.updated':
                case 'customer.subscription.deleted':
                    $this->handleSubscriptionUpdate($event->data->object);
                    break;
            }
            
            return true;
        } catch (\Exception $e) {
            $this->logError('Erro no webhook', $e);
            return false;
        }
    }

    private function checkStripeSubscription($userEmail)
    {
        $customers = Customer::all(['email' => $userEmail]);
        if (empty($customers->data)) {
            return false;
        }

        $customer = $customers->data[0];
        $subscriptions = Subscription::all([
            'customer' => $customer->id,
            'status' => 'active'
        ]);

        return count($subscriptions->data) > 0;
    }

    private function handleSubscriptionUpdate($subscription)
    {
        $userId = $this->getUserByStripeCustomerId($subscription->customer);
        if (!$userId) {
            return;
        }

        $isPremium = $subscription->status === 'active';
        update_user_meta($userId, 'is_premium', $isPremium);
        $this->cacheUserPremiumStatus($userId, $isPremium);
    }

    private function cacheUserPremiumStatus($userId, $status)
    {
        set_transient('user_premium_status_' . $userId, $status, self::CACHE_DURATION);
    }

    private function getUserByStripeCustomerId($customerId)
    {
        global $wpdb;
        return $wpdb->get_var($wpdb->prepare(
            "SELECT user_id FROM {$wpdb->usermeta} 
            WHERE meta_key = 'stripe_customer_id' 
            AND meta_value = %s",
            $customerId
        ));
    }

    private function logError($message, \Exception $e)
    {
        error_log(sprintf(
            '[StripeService] %s: %s',
            $message,
            $e->getMessage()
        ));
    }


/**
 * Cancela a assinatura premium do usuário
 * 
 * @param int $userId ID do usuário no WordPress
 * @return array Resultado da operação com status e mensagem
 */

public function cancelSubscription($userId)
{
    try {
        $user = get_user_by('id', $userId);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Usuário não encontrado'
            ];
        }

        // Busca o cliente no Stripe
        $customers = Customer::all(['email' => $user->user_email]);
        if (empty($customers->data)) {
            return [
                'success' => false,
                'message' => 'Nenhuma assinatura encontrada'
            ];
        }

        $customer = $customers->data[0];
        
        // Busca assinaturas ativas
        $subscriptions = Subscription::all([
            'customer' => $customer->id,
            'status' => 'active'
        ]);

        if (empty($subscriptions->data)) {
            return [
                'success' => false,
                'message' => 'Nenhuma assinatura ativa encontrada'
            ];
        }

        // Cancela a assinatura
        $subscription = $subscriptions->data[0];
        $subscription->cancel();

        // Atualiza o meta do usuário
        update_user_meta($userId, 'is_premium', false);
        $this->cacheUserPremiumStatus($userId, false);

        return [
            'success' => true,
            'message' => 'Assinatura cancelada com sucesso'
        ];

    } catch (\Exception $e) {
        $this->logError('Erro ao cancelar assinatura', $e);
        return [
            'success' => false,
            'message' => 'Erro ao processar o cancelamento'
        ];
    }
}

}
