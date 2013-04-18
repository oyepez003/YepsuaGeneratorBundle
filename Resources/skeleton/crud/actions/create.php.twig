
    /**
     * Creates a new {{ entity }} entity.
     *
{% if 'annotation' == format %}
     * @Route("/create", name="{{ route_name_prefix }}_create")
     * @Method("POST")
     * @Template("{{ bundle }}:{{ entity }}:new.html.twig")
{% endif %}
     */
    public function createAction(Request $request)
    {
        try{
            $entity  = new {{ entity_class }}();
            $form = $this->createForm(new {{ entity_class }}Type(), $entity);
            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                $deleteForm = $this->createDeleteForm($entity->getId());
                {% if 'show' in actions -%}

                return $this->render('{{ bundle }}:{{ entity }}:show.html.twig',array(
                  'entity'      => $entity,
                  'delete_form' => $deleteForm->createView(),)
                );

                {%- else -%}
                    return $this->redirect($this->generateUrl('{{ route_name_prefix }}'));
                {%- endif %}

            }
            
            return $this->render('{{ bundle }}:{{ entity|replace({'\\': '/'}) }}:new.html.twig', array(
                'entity' => $entity,
                'form'   => $form->createView(),
            ), new Response(null, 202));
                        
        }catch(\Exception $e){
          $this->get('logger')->crit($e->getMessage());
          return new Response(Notification::error($e->getMessage()));
        }
    }
