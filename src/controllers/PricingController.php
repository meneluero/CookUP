<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;

final class PricingController extends AppController
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function index(): void
    {
        $userId = $this->currentUserId();
        $currentRole = $userId === null ? null : $this->userRepository->find($userId)?->getRole();

        $this->render('pricing', [
            'pageTitle' => 'Pricing | CookUP+',
            'activeNav' => 'pricing',
            'notice' => isset($_GET['upgraded']) ? 'Your account is now Pro. Upload as many loops as you like.' : null,
            'plans' => [
                [
                    'name' => 'Starter',
                    'price' => '$0',
                    'note' => 'Free forever',
                    'features' => [
                        'Upload up to ' . User::STARTER_LOOP_LIMIT . ' loops',
                        'Download loops as MP3',
                        'Send collabs to other producers',
                        'Save favorite loops',
                    ],
                    'highlighted' => false,
                    'cta' => $this->planAction(User::ROLE_STARTER, $currentRole),
                ],
                [
                    'name' => 'Pro',
                    'price' => '$0',
                    'note' => 'Free during the beta - no payment needed',
                    'features' => [
                        'Unlimited loop uploads',
                        'Download loops as lossless WAV or MP3',
                        'Send collabs to other producers',
                        'Save favorite loops',
                    ],
                    'highlighted' => true,
                    'cta' => $this->planAction(User::ROLE_PRO, $currentRole),
                ],
            ],
            'comparison' => [
                ['feature' => 'Loop uploads', 'starter' => 'Up to ' . User::STARTER_LOOP_LIMIT, 'pro' => 'Unlimited'],
                ['feature' => 'Download format', 'starter' => 'MP3', 'pro' => 'WAV or MP3'],
                ['feature' => 'Collabs and favorites', 'starter' => 'Yes', 'pro' => 'Yes'],
            ],
        ]);
    }

    public function upgrade(): void
    {
        // tylko Starter może przejść na Pro - Pro i Admin dostają 403, gość trafia na logowanie
        $user = $this->requireRole($this->userRepository, User::ROLE_STARTER);
        $this->requireCsrfToken();

        // bez płatności - Upgrade tylko zmienia rolę w bazie
        $this->userRepository->updateRole($user->getId(), User::ROLE_PRO);

        $this->redirect('/pricing?upgraded=1');
    }

    /**
     * Co widać pod planem: link (gość), przycisk Upgrade (Starter przy planie Pro) albo sam napis.
     *
     * @return array{type: 'link'|'upgrade'|'label', label: string, href?: string}
     */
    private function planAction(string $planRole, ?string $currentRole): array
    {
        if ($currentRole === null) {
            return $planRole === User::ROLE_STARTER
                ? ['type' => 'link', 'label' => 'Create free account', 'href' => '/register']
                : ['type' => 'link', 'label' => 'Sign in to upgrade', 'href' => '/login'];
        }

        if ($currentRole === $planRole) {
            return ['type' => 'label', 'label' => 'Your current plan'];
        }

        if ($currentRole === User::ROLE_STARTER) {
            return ['type' => 'upgrade', 'label' => 'Upgrade to Pro'];
        }

        return ['type' => 'label', 'label' => $currentRole === User::ROLE_ADMIN ? 'Included for admins' : 'Included in Pro'];
    }
}
