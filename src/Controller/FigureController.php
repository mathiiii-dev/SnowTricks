<?php

namespace App\Controller;

use App\Entity\Figure;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @Route("/figure/{id}", name="snowtricks_figure")
     */
    public function index($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Figure::class);

        $figure = $repository->find($id);

        return $this->render('figure/index.html.twig', [
            'figure' => $figure
        ]);
    }

    /**
     * @Route("/create-figure", name="snowtricks_createfigure")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    public function createFigure(Request $request, EntityManagerInterface $em): Response
    {
        $figure = new Figure();

        $form = $this->createFormBuilder($figure)
            ->add('name', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nom',
                    'class' =>'appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Description',
                    'class' => 'appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                ]
            ])
            ->add('figure_group', TextType::class, [
                'attr' => [
                    'placeholder' => 'Figure group',
                    'class' => 'block appearance-none text-gray-600 w-full bg-white border border-gray-400 shadow-inner px-4 py-2 pr-8 rounded',
                ]
            ])
            ->add('picture', TextType::class,
                [
                    'attr' => [
                        'placeholder' => 'Photos',
                        'class' => 'appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                    ]
                ])
            ->add('video', TextType::class,
                [
                    'attr' => [
                        'placeholder' => 'Videos',
                        'class' => 'appearance-none block w-full bg-white text-gray-700 border border-gray-400 shadow-inner rounded-md py-3 px-4 leading-tight focus:outline-none  focus:border-gray-500'
                    ]
                ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => [
                    'class' => 'appearance-none bg-gray-200 text-gray-900 px-2 py-1 shadow-sm border border-gray-400 rounded-md mr-3'
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $figure->setCreatedAt(new \DateTime());
            $repository = $this->getDoctrine()->getRepository(User::class);
            $user = $repository->findOneBy(['pseudo' => 'admin']);
            $figure->setUser($user);
            $em->persist($figure);
            $em->flush();

            return $this->redirectToRoute('snowtricks_home');
        }

        return $this->render('figure/createFigure.html.twig', [
            'formFigure' => $form->createView()
        ]);
    }
}
