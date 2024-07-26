<?php

return [
  "sections"=> [
    "listnegatives"=> array(
      "id"=>1,
      "icon"=>"assets/icons/users.svg",
      "title"=>"Listas Negativas",
      "isActive"=>true,
    ),
    "listnegatives_search"=> array(
      "id"=>2,
      "icon"=>"assets/icons/companies.svg",
      "title"=>"Listas Negativas - Búsquedas",
      "isActive"=>true,
    ),
    "listnegatives_admin"=> array(
      "id"=>3,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Lista Negativas - Administrador",
      "isActive"=>true,
    ),
    "risksmatrix"=> array(
      "id"=>4,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Matriz de Riesgos",
      "isActive"=>false,
    ),
    "risksscoring"=> array(
      "id"=>5,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Scoring de Riesgo",
      "isActive"=>false,
    ),
    "complaint_channel"=> array(
      "id"=>6,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Canal de Denuncia",
      "isActive"=>false,
    ),
    "operations_register"=> array(
      "id"=>7,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Registro de Operaciones",
      "isActive"=>false,
    ),
    "operations_report"=> array(
      "id"=>8,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Reporte de Operaciones",
      "isActive"=>false,
    ),
    "courses"=> array(
      "id"=>9,
      "icon"=>"assets/icons/modules.svg",
      "title"=>"Mis cursos",
      "isActive"=>false,
    ),
  ],
  "excel" => [
    "maxRowsNegLists" => 520,
    "maxRowsNegListsAdmin" => 1001,
  ],
  "probsMap" => [
    [0.01, 4.99],
    [5.00, 9.99],
    [10.00, 29.99],
    [30.00, 99.90],
    [99.91, 100.00],
  ],
  "impactsMap" => [
    "0" => [[0, 1], [ 1, 10], [10, 20], [20, 40], [40, 150]],
    "1" => [[0, 1], [ 1, 50], [50, 100], [100, 200], [200, 1700]],
    "2" => [[0, 1], [ 1, 250], [250, 300], [300, 450], [450, 2300]],
    "3" => [[0, 1], [ 1, 250], [250, 300], [300, 450], [450, 2400]],
  ],
];

?>