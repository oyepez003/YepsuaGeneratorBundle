
    /**
     * Deletes a {{ entity }} entity.
     *
{% if 'annotation' == format %}
     * @Route("/{id}", name="{{ route_name_prefix }}_delete")
     * @Method({"DELETE", "POST"})
{% endif %}
     */
    public function deleteAction(Request $request, $id)
    {
        try{
            if(strtoupper($request->getMethod()) == "DELETE"){
              $form = $this->createDeleteForm($id);
            }else{
              $form = $this->createDeleteForm($id,array('csrf_protection' => false));
            }
            $form->bind($request);
            $id = strpos($id,',') ? explode(',', $id) : array($id);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $entities = $em->getRepository('{{ bundle }}:{{ entity }}')->findById($id);

                foreach ($entities as $entity){
                  if (!$entity) {
                    throw $this->createNotFoundException('Unable to find {{ entity }} entity.');
                  }
                  $em->remove($entity);
                  $em->flush();
                }
            }
            return new Response();
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()), 203);
        }
    }

    /**
     * Creates a form to delete a {{ entity }} entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id, array $options = array())
    {
        return $this->createFormBuilder(array('id' => $id), $options)
            ->add('id', 'hidden')
            ->getForm()
        ;
    }