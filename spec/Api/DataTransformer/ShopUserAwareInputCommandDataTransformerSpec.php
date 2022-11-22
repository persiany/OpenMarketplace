<?php

declare(strict_types=1);

namespace spec\BitBag\OpenMarketplace\Api\DataTransformer;

use BitBag\OpenMarketplace\Api\DataTransformer\ShopUserAwareInputCommandDataTransformer;
use BitBag\OpenMarketplace\Api\Messenger\Command\ShopUserAwareInterface;
use BitBag\OpenMarketplace\Entity\ShopUserInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ApiBundle\Context\UserContextInterface;
use Sylius\Bundle\ApiBundle\DataTransformer\CommandDataTransformerInterface;
use Sylius\Component\User\Model\UserInterface;

class ShopUserAwareInputCommandDataTransformerSpec extends ObjectBehavior
{
    public function let(
        UserContextInterface $userContext
    ): void {
        $this->beConstructedWith($userContext);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ShopUserAwareInputCommandDataTransformer::class);
        $this->shouldImplement(CommandDataTransformerInterface::class);
    }

    public function is_supports_shop_user_aware_interface(
        ShopUserAwareInterface $shopUserAware
    ): void {
        $this->supportsTransformation($shopUserAware)->shouldReturn(true);
    }

    public function it_set_shop_user_from_context(
        ShopUserAwareInterface $shopUserAware,
        UserContextInterface $userContext,
        ShopUserInterface $shopUser
    ): void {
        $shopUserAware->getShopUser()->willReturn(null);
        $userContext->getUser()->willReturn($shopUser);

        $shopUserAware->setShopUser($shopUser)->shouldBeCalled();

        $this->transform($shopUserAware, '');
    }

    public function it_does_nothing_if_user_is_not_shop_user_context(
        ShopUserAwareInterface $shopUserAware,
        UserContextInterface $userContext,
        UserInterface $user
    ): void {
        $shopUserAware->getShopUser()->willReturn(null);
        $userContext->getUser()->willReturn($user);

        $shopUserAware->setShopUser($user)->shouldNotBeCalled();

        $this->transform($shopUserAware, '');
    }

    public function it_does_nothing_if_shop_user_already_set(
        ShopUserAwareInterface $shopUserAware,
        UserContextInterface $userContext,
        ShopUserInterface $shopUser
    ): void {
        $shopUserAware->getShopUser()->willReturn($shopUser);
        $userContext->getUser()->willReturn($shopUser);

        $shopUserAware->setShopUser($shopUser)->shouldNotBeCalled();

        $this->transform($shopUserAware, '');
    }
}
