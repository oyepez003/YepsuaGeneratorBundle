
    /**
     * Lists all {{ entity }} entities.
     *
{% if 'annotation' == format %}
     * @Route("/", name="{{ route_name_prefix }}")
     * @Method("GET")
     * @Template()
{% endif %}
     */
    public function indexAction()
    {
        JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
        JQuery::usePlugin(JQueryConstant::PLUGIN_PNOTYFY);
        
        $grid = new Grid('{{ entity|lower }}','{{ entity }} List');
        $grid->setUrl($this->generateUrl('{{ route_name_prefix }}_data'));
        $grid->setTranslator($this->get('translator'));
        {% if not ('new' in actions) and not ('edit' in actions) and not ('delete' in actions) -%}
        $grid->noWriteActions();
        {% endif -%}
        $grid->get();
        
        /* The columns */
      {%- for field, metadata in fields %}
        
        $field = new GridField('{{ entity|lower }}.{{field|replace({'_': ''})}}', '{{ field|capitalize }}');
      {% if metadata.id is defined %}
  $field->setHidden(true);
      {% endif %}
  $grid->addGridField($field);
      {%- endfor %}
        
      
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('{{ bundle }}:{{ entity }}')->findAll();

{% if 'annotation' == format %}
        return array(
            'grid' => $grid
        );
{% else %}
        return $this->render('{{ bundle }}:{{ entity|replace({'\\': '/'}) }}:index.html.twig', array(
            'grid' => $grid,
        ));
{% endif %}
    }

    /**
     * Public service - All {{ entity_class }} entities
{% if 'annotation' == format %}
     * @Route("/data", name="{{ route_name_prefix }}_data")
     * @Method("GET")
{% endif %}
     */
    public function dataAction()
    {
        try{
            JQuery::useComponent(JQueryConstant::COMPONENT_JQGRID);
            $em = $this->getDoctrine()->getManager();
            $orderBy = $this->getRequest()->get('sidx');
            if ($orderBy) {
                $sord = $this->getRequest()->get('sord', 'ASC');
                $repository = $em->getRepository('{{ bundle }}:{{ entity }}');
                $filters = $this->getRequest()->get('filters', null);
                $query = Dao::buildQuery($repository, '{{ entity|lower }}', $orderBy, $sord, $filters);
                $entities = $query->getResult();
            } else {
                $entities = $em->getRepository('{{ bundle }}:{{ entity }}')->findAll();
            }

            $page = $this->getRequest()->get("page", 1);
            $rows = $this->getRequest()->get("rows", 1);
            $paginator = new Paginator($page, $rows);

            $totalRows = sizeof($entities) / $rows;
            $totalRows = is_real($totalRows) ? intval($totalRows) + 1 : intval($totalRows);
            $response = new GridResponse();
            $response->setPage($page);
            $response->setTotal($totalRows);
            $response->setRecords(sizeof($entities));

            foreach ($paginator->paginate($entities) as $entitie){
                $row = new GridRow();
                $row->setId($entitie->getId());
    {%- for field, metadata in fields %}

      {%- if metadata.type in ['date', 'datetime'] %}

                $row->newCell($entitie->get{{field|replace({'_': ''})}}()->format('Y-m-d H:i:s'));

      {%- else %}

                $row->newCell($entitie->get{{field|replace({'_': ''})}}());

      {%- endif %}

    {%- endfor %}
    
                $response->addGridRow($row);
            }
            return new Response($response->buildResponseAsJSON());
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()));
        }
    }
