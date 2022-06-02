<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * You can find more information about us on https://bitbag.io and write us
 * an email on hello@bitbag.io.
 */

declare(strict_types=1);

namespace BitBag\SyliusMultiVendorMarketplacePlugin\Controller;

use BitBag\SyliusMultiVendorMarketplacePlugin\Entity\VendorInterface;
use BitBag\SyliusMultiVendorMarketplacePlugin\Exception\UserNotFoundException;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class VendorController extends ResourceController
{
    public function createAction(Request $request): Response
    {
        try {
            return parent::createAction($request);
        } catch (UserNotFoundException|TokenNotFoundException $exception) {
            return $this->redirectToRoute('sylius_shop_login');
        }
    }

    public function showVendorPageAction(Request $request): Response
    {
        /** @var VendorInterface $vendor */
        $vendor = $this->repository->findOneBy(['slug' => $request->attributes->get('slug')]);

        $productRepository = $this->container->get('bit_bag.sylius_multi_vendor_marketplace_plugin.repository.product_repository');
        $paginator = $productRepository->findVendorProducts($vendor, $request);

        return $this->render('@BitBagSyliusMultiVendorMarketplacePlugin/vendor/vendor_page.html.twig', [
            'vendor' => $vendor,
            'imagesDir' => '/media/image/',
            'paginator' => $paginator,
        ]);
    }
}
