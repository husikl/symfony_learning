<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\Article1Type;
use App\Form\SearchForm;
use App\Model\ArticleFilter;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_index", methods={"GET"})
     * @param ArticleRepository $articleRepository
     * @return Response
     */
    public function index(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator, ValidatorInterface $validator): Response
    {
        $form = $this->createForm(SearchForm::class);
        $form->handleRequest($request);

        $queryBuilder = $articleRepository->createQueryBuilder('a');

        if ($form->isSubmitted() && $form->isValid())
        {
            /** @var ArticleFilter $articleFilter */
            $articleFilter = $form->getData();

            $searchId = $articleFilter->getId();

            if ($searchId) {
                $queryBuilder->andWhere('a.id like  :id')
                    ->orderBy('a.id', 'ASC')
                    ->setParameter('id',  $searchId.'%' );
            }

            /** @var \DateTimeImmutable $searchDate */
            $searchDate = $articleFilter->getDate();
            if ($searchDate) {
                $queryBuilder->andWhere('a.publishedAt = :date')
                    ->setParameter('date', $searchDate);
            }
            /** @var string $searchAuthors */
            $searchAuthors = $articleFilter->getAuthors();
            if ($searchAuthors) {
                $queryBuilder->andWhere('a.Authors like :authors ')
                    ->setParameter('authors', '%' . $searchAuthors . '%');
            }

            /** @var string $searchContent */
            $searchContent = $articleFilter->getContent();
            if ($searchContent) {
                $queryBuilder->andWhere('a.content like :content')
                    ->setParameter('content', '%' . $searchContent . '%');
            }
        }


//        $errors = $validator->validate($form);
//        if (count($errors) > 0) {
//            $errorsString = (string)$errors;
//            return new Response($errorsString);
//        }
        $pagination = $paginator ->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1) /*page number*/,
            3 /*pages per page*/
        );

        return $this->render('article/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form -> createView(),
        ]);
    }

    /**
     * @Route("/add", name="article_new", methods={"GET","POST"})
     */
    public function create(ValidatorInterface $validator, Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_index');
        }

        $errors = $validator->validate($article);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
        return $this->render('article/new.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="article_show", methods={"GET"})
     */
    public function show(Article $article /*, EntityManagerInterface $em*/): Response
    {
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="article_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(Article1Type::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('article_index');
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'form' => $form -> createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
