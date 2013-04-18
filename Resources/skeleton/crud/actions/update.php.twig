
    /**
     * Edits an existing {{ entity }} entity.
     *
{% if 'annotation' == format %}
     * @Route("/{id}", name="{{ route_name_prefix }}_update")
     * @Method("PUT")
     * @Template("{{ bundle }}:{{ entity }}:edit.html.twig")
{% endif %}
     */
    public function updateAction(Request $request, $id)
    {
        try{
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('{{ bundle }}:{{ entity }}')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find {{ entity }} entity.');
            }

            $deleteForm = $this->createDeleteForm($id);
            $editForm = $this->createForm(new {{ entity_class }}Type(), $entity);
            $editForm->bind($request);

            if ($editForm->isValid()) {
                $em->persist($entity);
                $em->flush();

                return $this->render('{{ bundle }}:{{ entity }}:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );
            }

            return $this->render('{{ bundle }}:{{ entity|replace({'\\': '/'}) }}:edit.html.twig', array(
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ), new Response(null, 202));
            
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()));
        }
    }
