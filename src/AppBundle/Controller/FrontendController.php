<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Album;
use AppBundle\Form\AlbumType;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{
    /**
     * @Route("/{page}", requirements={"page" = "\d+"}, name="frontend_index", defaults={"page" = 1})
     *
     * @param int     $page
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \LogicException
     */
    public function indexAction($page, Request $request)
    {
        $parameters = array();

        $album = $this->get('app.album.service');
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $album->getListWithImageMinQueryBuilder()->getQuery(),
            $page,
            $countItemsPerPage = 10
        );

        $parameters['pagination'] = $pagination;

        return $this->render('@App/index.html.twig', $parameters);
    }

    /**
     * @Route("/albums/{page}", requirements={"page" = "\d+"}, name="frontend_all_albums", defaults={"page" = 1})
     *
     * @param int     $page
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \LogicException
     */
    public function allAlbumsAction($page, Request $request)
    {
        $parameters = array();

        $album = $this->get('app.album.service');
        $paginator = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $album->getListQueryBuilder()->getQuery(),
            $page,
            $countItemsPerPage = 10
        );

        $parameters['pagination'] = $pagination;

        return $this->render('@App/index.html.twig', $parameters);
    }

    /**
     * @Route("/album", name="frontend_new_album")
     */
    public function newAlbumAction(Request $request)
    {
        $albumService = $this->get('app.album.service');
        $parameters = array();

        $album = new Album();
        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $albumService->save($album);
            } catch (\Exception $ex) {
                // save exception and show it for user
            }

            return $this->redirect($this->generateUrl('frontend_index'));
        }

        $parameters['form'] = $form->createView();

        return $this->render('@App/form_album.html.twig', $parameters);
    }

    /**
     * @Route("/album/{id}/show", requirements={"id" = "\d+"}, name="frontend_show_album", defaults={"id" = null})
     *
     * @param null    $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \InvalidArgumentException
     */
    public function showAlbumAction($id, Request $request)
    {
        $urlReturnToMainPage = $this->generateUrl('frontend_index');
        if (null === $id) {
            return new RedirectResponse($urlReturnToMainPage);
        }

        $parameters = array();
        // $parameters['album'] = $this->get('app.album.service')->getRecordById($id);
        $parameters['album'] = $this->get('app.album.service')->getByIdFilterMaxImages($id);
        $parameters['imagePath'] = $this->get('app.file_uploader.service')->getTargetDir();

        return $this->render('@App/show_album.html.twig', $parameters);
    }

    /**
     * @Route("/album/{id}/edit", requirements={"id" = "\d+"}, name="frontend_edit_album", defaults={"id" = null})
     *
     * @param null    $id
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @throws \InvalidArgumentException
     */
    public function editAlbumAction($id, Request $request)
    {
        $urlReturnToMainPage = $this->generateUrl('frontend_index');
        if (null === $id) {
            return new RedirectResponse($urlReturnToMainPage);
        }

        $albumService = $this->get('app.album.service');
        $parameters = array();

        /** @var Album $album */
        $album = $albumService->getRecordById($id);

        $originalImages = new ArrayCollection();
        foreach ($album->getImages() as $image) {
            $originalImages->add($image);
        }

        $form = $this->createForm(AlbumType::class, $album);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $albumService->save($album, $originalImages);
            } catch (\Exception $ex) {
                // save exception and show it for user
            }

            return $this->redirect($this->generateUrl('frontend_index'));
        }

        $uploader = $this->get('app.file_uploader.service');

        $parameters['imagePath'] = $uploader->getTargetDir();
        $parameters['form'] = $form->createView();

        return $this->render('@App/form_album.html.twig', $parameters);
    }
}
