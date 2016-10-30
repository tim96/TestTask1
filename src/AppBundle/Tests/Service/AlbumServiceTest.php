<?php
/**
 * Created by PhpStorm.
 * User: tim
 * Date: 10/30/2016
 * Time: 7:19 PM.
 */

namespace AppBundle\Tests\Service;

use AppBundle\Service\AlbumService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumServiceTest extends \PHPUnit_Framework_TestCase
{
    public function testGetById()
    {
        $id = 1;
        $album = $this->createMock('AppBundle\Entity\Album');

        $albumRepository = $this->getMockBuilder('AppBundle\Repository\AlbumRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $albumRepository->expects($this->once())
            ->method('getById')
            ->with($id)
            ->will($this->returnValue(array($album)));

        $em = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->with('AppBundle:Album')
            ->will($this->returnValue($albumRepository));

        $albumService = new AlbumService($em);
        $this->assertCount(1, $albumService->getById($id));
    }

    public function testGetRecordById()
    {
        $id = 1;
        $album = $this->createMock('AppBundle\Entity\Album');

        $albumRepository = $this->getMockBuilder('AppBundle\Repository\AlbumRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $albumRepository->expects($this->once())
            ->method('getById')
            ->with($id)
            ->will($this->returnValue(array($album)));

        $em = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->with('AppBundle:Album')
            ->will($this->returnValue($albumRepository));

        $albumService = new AlbumService($em);
        $this->assertEquals($album, $albumService->getRecordById($id));
    }

    public function testGetRecordByIdException()
    {
        $id = 1;

        $albumRepository = $this->getMockBuilder('AppBundle\Repository\AlbumRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $albumRepository->expects($this->once())
            ->method('getById')
            ->with($id)
            ->will($this->throwException(new NotFoundHttpException()));

        $em = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->disableOriginalConstructor()
            ->getMock();
        $em->expects($this->once())
            ->method('getRepository')
            ->with('AppBundle:Album')
            ->will($this->returnValue($albumRepository));

        $albumService = new AlbumService($em);

        $this->expectException(NotFoundHttpException::class);
        $albumService->getRecordById($id);
    }
}
