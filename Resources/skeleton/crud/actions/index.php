
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
        
      {%- if not associationMappings is empty  %}
      
        $em = $this->getDoctrine()->getManager();
        
      {%- endif %}
      
      {% for field, metadata in fields %}
      
        $field = new GridField('{{ entity|lower }}.{{field}}', '{{ field|replace({'_': ' '})|title }}');
      {%- if metadata.id is defined %}
      
        $field->setHidden(true);
      {%- endif %}
      
        $grid->addGridField($field);
      {% endfor -%}
      
      {% for association in associationMappings %}
        {% set fieldName = association.fieldName %}
        
        $field = new GridField('{{fieldName}}.id', '{{fieldName|replace({'_': ' '})|capitalize|replace({' ': ''})}}');
        $field->setSearchOptions(array(
          'value' => ':' . ObjectUtil::entityToKeyValue(
            $em->getRepository('{{association.targetEntity}}')->findAll(), ";%KEY%:%VALUE%" 
          )
        ));        
        $field->setSType(GridConstants::EDIT_TYPE_SELECT);
        $grid->addGridField($field);
      {%- endfor %}
      
      {# for key in associationMappings|keys %}
        {{ key }}:
        {% for key2 in associationMappings[key]|keys  %}
          {{ key2 }}: {{ associationMappings[key][key2] }}
          {% set value2 = associationMappings[key][key2] %}
          {% if value2 is iterable  %}
            {% for key3 in value2|keys  %}
            {{ key3 }}: {{ value2[key3] }}
            {% endfor %}
          {% endif %}
        {% endfor %}
      {% endfor #}
      
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

    public function entityToKeyValue($entities, $pattern =";%KEY%:%VALUE%"){
      $toStringVal = "";
      foreach($entities as $entitie){
         $_pattern = $pattern;
         $_pattern = str_replace('%KEY%', $entitie->getId(), $_pattern);
         $_pattern = str_replace('%VALUE%', $entitie->__toString(), $_pattern);
         $toStringVal .= $_pattern;
      }
      return $toStringVal;
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
                {% for association in associationMappings -%}
                $query = $query->leftJoin('{{ entity|lower }}.{{ association.fieldName }}','{{ association.fieldName }}');
                {% endfor -%}
                $entities = $query->getQuery()->getResult();
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

                $row->newCell($entitie->get{{field|replace({'_': ' '})|capitalize|replace({' ': ''})}}()->format('Y-m-d H:i:s'));

      {%- else %}

                $row->newCell($entitie->get{{field|replace({'_': ' '})|capitalize|replace({' ': ''})}}());

      {%- endif %}

    {%- endfor %}
    {%- for association in associationMappings %}
    
                $row->newCell((string) $entitie->get{{association.fieldName|replace({'_': ' '})|capitalize|replace({' ': ''})}}());
                
    {%- endfor %}
    
                $response->addGridRow($row);
            }
            
            return new Response($response->buildResponseAsJSON());
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()));
        }
    }
