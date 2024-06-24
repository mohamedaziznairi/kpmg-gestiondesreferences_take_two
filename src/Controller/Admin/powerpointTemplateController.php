<?php

// src/Controller/Admin/CustomPageController.php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class powerpointTemplateController extends AbstractController
{
    #[Route('/admin/powerpointTemplate', name: 'powerpointTemplate')]
    public function index(): Response
    {
        return $this->render('powerpointTemplate.html.twig');
    }
}
