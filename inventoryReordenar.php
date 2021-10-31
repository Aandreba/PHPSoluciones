<?php

function add ($item) {
    /* Esto deberias tenerlo hecho ya.
        Es importante que este metodo añada los nuevos items de forma ordenada (es decir en el primer null que encuentre, no cualquiera)
    */
}

// Bacia el inventario
function clear ($items) : void {
    $items = array_fill(0, count($items), array_fill(0, count($items[0]), null));
}

// Reordena el inventario
function reshape ($items) {
    $byType = [];

    // Separa cada item por classes
    for ($i=0;$i<count($items);$i++) {
        for ($j=0;$j<count($items[$i]);$j++) {
            $item = $items[$i][$j];
            if ($item == null) {
                return;
            }
    
            $type = get_class($item); // Obtiene el nombre de la classe principal del objecto
            if (array_key_exists($type, $byType)) {
                array_push($byType[$type], $item); // Si ya existen elementos en el array con este tipo, añade este a la lista
            } else {
                $byType[$type] = [$item]; // Sino, crea una nueva lista con este elemento
            }
        }
    }

    clear($items); // Bacia el inventario

    foreach ($byType as $key => $elements) { // $key = nombre de la classe; $elements = elementos de la classe especificada
        foreach ($elements as $element) { // Por cada elemento dentro de la lista de elementos
            add($element); // Añade el elemento a la nueva lista
        }
    }
}

?>