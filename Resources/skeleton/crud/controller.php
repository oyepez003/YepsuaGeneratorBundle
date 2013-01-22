<?php

namespace {{ namespace }}\Controller{{ entity_namespace ? '\\' ~ entity_namespace : '' }};

use Symfony\Component\HttpFoundation\Response;
{% if 'new' in actions or 'edit' in actions or 'delete' in actions %}
use Symfony\Component\HttpFoundation\Request;
{%- endif %}

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
{% if 'annotation' == format -%}
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
{%- endif %}

use {{ namespace }}\Entity\{{ entity }};
{% if 'new' in actions or 'edit' in actions %}
use {{ namespace }}\Form\{{ entity }}Type;
{% endif %}

use Yepsua\GeneratorBundle\UI\Grid;
use Yepsua\CommonsBundle\Persistence\Dao;
use Yepsua\CommonsBundle\IO\Paginator;
use Yepsua\SmarTwigBundle\UI\Message\Notification;

use \YsJQuery as JQuery;
use \YsJQueryConstant as JQueryConstant;
use \YsGridField as GridField;
use \YsGridResponse as GridResponse;
use \YsGridRow as GridRow;

/**
 * {{ entity }} controller.
 *
{% if 'annotation' == format %}
 * @Route("/{{ route_prefix }}")
{% endif %}
 */
class {{ entity_class }}Controller extends Controller
{

    {%- if 'index' in actions %}
        {%- include 'actions/index.php' %}
    {%- endif %}

    {%- if 'new' in actions %}
        {%- include 'actions/create.php' %}
        {%- include 'actions/new.php' %}
    {%- endif %}

    {%- if 'show' in actions %}
        {%- include 'actions/show.php' %}
    {%- endif %}

    {%- if 'edit' in actions %}
        {%- include 'actions/edit.php' %}
        {%- include 'actions/update.php' %}
    {%- endif %}

    {%- if 'delete' in actions %}
        {%- include 'actions/delete.php' %}
    {%- endif %}

}
