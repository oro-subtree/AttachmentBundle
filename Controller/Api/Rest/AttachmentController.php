<?php

namespace Oro\Bundle\AttachmentBundle\Controller\Api\Rest;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Routing\ClassResourceInterface;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Entity\Manager\ApiEntityManager;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;

/**
 * @RouteResource("attachment")
 * @NamePrefix("oro_api_")
 */
class AttachmentController extends RestController implements ClassResourceInterface
{
    /**
     * REST DELETE
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete Attachment",
     *      resource=true
     * )
     * @ Acl(
     *      id="oro_attachment_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="OroAttachmentBundle:Attachment"
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * Get entity Manager
     *
     * @return ApiEntityManager
     */
    public function getManager()
    {
        return $this->get('oro_attachment.manager.api');
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        //return $this->get('oro_attachment.f');
    }

    /**
     * @return ApiFormHandler
     */
    public function getFormHandler()
    {
        //return $this->get('orocrm_task.form.handler.task_api');
    }
}
