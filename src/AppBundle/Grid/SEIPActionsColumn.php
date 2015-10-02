<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace AppBundle\Grid;
use APY\DataGridBundle\Grid\Column\Column;
/**
 * Description of SEIPActionsColums
 *
 * @author Jose
 */
class SEIPActionsColumn extends Column {
    
    protected $route_to_edit = "";
    protected $route_to_delete = "";
    /**
     * Se utiliza para grids que tienen dos niveles y es necesario pasarle un parametro adicional a la ruta
     * @var type 
     */
    protected $idRutaAdicional = 0;
    
    public function __initialize(array $params)
    {
        $params['filterable'] = false;
        $params['sortable'] = false;
        $params['size'] = 50;
        parent::__initialize($params);
    }
    public function getType()
    {
        return 'SEIPActions';
    }
    
    
    public function setEditRoute($route)
    {
        $this->route_to_edit = $route;
    }
    
    public function getEditRoute ()
    {
        return $this->route_to_edit;
    }
    
    public function setDeleteRoute($route)
    {
        $this->route_to_delete = $route;
    }
    
    public function getDeleteRoute ()
    {
        return $this->route_to_delete;
    }

    public function setIdRutaAdicional($id)
    {
        $this->idRutaAdicional = $id;
    }
    
    public function getIdRutaAdicional ()
    {
        return $this->idRutaAdicional;
    }

    
    /**
     * Draw cell
     *
     * @param string $value
     * @param Row $row
     * @param $router
     * @return string
     */
    public function renderCell($value, $row, $router)
    {
        if (is_callable($this->callback)) {
            return call_user_func($this->callback, $value, $row, $router);
        }
        
       $idLink = $row->getField('id');
       // Si el link es cero, quiere decir que no hay nada a lo cual apuntar, con lo cual no mostramos los links
       if ($idLink == 0)
           return "";
        
        $str = "";
        if ($this->route_to_edit != "")
        {
            if ($this->getIdRutaAdicional() != 0)
            {
                $replStr = $router->generate($this->route_to_edit, array('idAdicional' => $this->getIdRutaAdicional(),'id' => $idLink));
            }
            else
            {
                $replStr = $router->generate($this->route_to_edit, array('id' => $idLink));

            }

             $str = <<<HTML
                     <a class="linkficha commandGrid" href = "{$replStr}" onclick= "abrirLinkEnPopup(event, $(this));"><i class="fa fa-pencil-square-o fa-lg"></i></a>
HTML;
            
            
       }
       
        if ($this->route_to_delete != "")
       {
            if ($this->getIdRutaAdicional() != 0)
            {
                $replStr2 = $router->generate($this->route_to_delete, array('idAdicional' => $this->getIdRutaAdicional(),'id' => $row->getField('id')));
            }
            else
            {
                $replStr2 = $router->generate($this->route_to_delete, array('id' => $row->getField('id')));
            }
           
             $str .= <<<HTML
                <a class="deleteficha" href = "{$replStr2}" target="_self" onclick= "deleteElement(event, $(this))" ><i class="fa fa-times fa-lg"></i></a>
HTML;
       }
                
       return $str;
                        
               /*             return htmlentities("<a class='linkficha' href='".
                        $router->generate("centroatencionedit", array('id' => $row->getField('id'))).
                        "' ng-click='EditElement()'>Editar");*/


    }
}
