<?php
/**
 * Created by PhpStorm.
 * User: Chilba Cristian
 * Date: 7/14/2018
 * Time: 11:40 PM
 */
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LuckyController
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index()
    {
        return $this->redirectToRoute('task_index', ['max' => 100]);
    }
}
