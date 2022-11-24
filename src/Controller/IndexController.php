<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Hub;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Jwt\FactoryTokenProvider;
use Symfony\Component\Mercure\Jwt\StaticTokenProvider;
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
    public function push(Request $request, HubInterface $hub)
    {
        // if you use http://127.0.0.1:8000 visit demo, no need change $hub
        $url = $request->getScheme() . '://' . $request->getHost() . '/.well-known/mercure';
        $jwt = $hub->getProvider();
        $hub = new Hub($url, $jwt);
        // if you use http://127.0.0.1:8000 visit demo, no need change $hub

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

    #[Route('/chat', name: 'chat')]
    public function chat(Request $request, HubInterface $hub)
    {
        // if you use http://127.0.0.1:8000 visit demo, no need change $hub
        $url = $request->getScheme() . '://' . $request->getHost() . '/.well-known/mercure';
        $jwt = $hub->getProvider();
        $hub = new Hub($url, $jwt);
        // if you use http://127.0.0.1:8000 visit demo, no need change $hub
        try {
            $update = new Update(
                'chat',
                $request->getContent()
            );
            $hub->publish($update);
        } catch (\Exception $e) {
            return $this->json([
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_GATEWAY);
        }
        return $this->json($request->getContent());
    }
}
