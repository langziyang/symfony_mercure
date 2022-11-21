<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    #[Route('/push', name: 'push')]
    public function push(HubInterface $hub)
    {
        try {
            $update = new Update(
                'books',
                json_encode(['status' => 'This is form server message' . (new \DateTime())->format('Y-m-d H:i:s')])
            );
            $hub->publish($update);
        } catch (\Exception $e) {
            dd($e);
        }

        return $this->json('done');
    }
}
