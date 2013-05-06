
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

        $field = new GridField('{{fieldName}}.id', '{{fieldName|replace({'_': ' '})|title }}');
        $field->setSearchOptions(array(
          'value' => ':' . ObjectUtil::entityToKeyValue(
            $em->getRepository('{{association.targetEntity}}')->findAll(), ";%KEY%:%VALUE%" 
          )
        ));        
        $field->setSType(GridConstants::EDIT_TYPE_SELECT);
        $grid->addGridField($field);
      {% endfor %}
      
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
            $page = $this->getRequest()->get("page", 1);
            $rows = $this->getRequest()->get("rows", 1);
            $sord = $this->getRequest()->get('sord', 'ASC');
            $filters = $this->getRequest()->get('filters', null);
            $response = new GridResponse();
            
            $repository = $em->getRepository('{{ bundle }}:{{ entity }}');
            $count = Dao::count($repository);
            
            if($count > 0){
                $query = Dao::buildQuery($repository, '{{ entity|lower }}', $orderBy, $sord, $filters);
                {% for association in associationMappings -%}
                $query = $query->leftJoin('{{ entity|lower }}.{{ association.fieldName }}','{{ association.fieldName }}');
                {% endfor -%}
                $query->setMaxResults($rows)->setFirstResult(($page - 1) * $rows);
                $entities = $query->getQuery()->getResult();
                
                foreach ($entities as $entitie){
                    $row = new GridRow();
                    $row->setId($entitie->getId());
    {%- for field, metadata in fields %}
      {% if '_' in field %}
        {% set fieldName = field|replace({'_': ' '})|title|replace({' ': ''}) %}
      {% else %}
        {% set fieldName = '' %}
        {% for char in field|split('') %}
          {%- if loop.first -%} 
            {%- set char = char|upper -%}
          {%- endif -%}
          {% set fieldName = fieldName ~ char %}
        {% endfor %}
      {% endif %}
      {%- if metadata.type in ['date', 'datetime'] %}
      
                    if($entitie->get{{fieldName}}() !== null){
                        $row->newCell($entitie->get{{fieldName}}()->format('Y-m-d H:i:s'));
                    }else{
                        $row->newCell($entitie->get{{fieldName}}());
                    }
      {%- elseif metadata.type == 'boolean' %}
      
                    $row->newCell($entitie->is{{fieldName}}());
      {%- else %}

                    $row->newCell($entitie->get{{fieldName}}());
      {%- endif -%}
    {%- endfor %}
    {%- for association in associationMappings %}
     {# for key in association|keys %}
        {{ key }}: {{ association[key] }}
     {% endfor #}
     
                    $row->newCell(ObjectUtil::__toString__($entitie->get{{association.fieldName|replace({'_': ' '})|title|replace({' ': ''})}}()));
                
    {%- endfor %}
    
                    $response->addGridRow($row);
                }
            }
            
            $totalRows = $count / $rows;
            $totalRows = is_real($totalRows) ? intval($totalRows) + 1 : intval($totalRows);
            $response->setPage($page);
            $response->setTotal($totalRows);
            $response->setRecords($count);

            return new Response($response->buildResponseAsJSON());    
        }catch(\Exception $e){
            $this->get('logger')->crit($e->getMessage());
            return new Response(Notification::error($e->getMessage()), 203);
        }
    }
