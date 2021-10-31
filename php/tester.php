<?php
require_once("classes.php");

$player1 = new Player();
$player2 = new Player();

$player1->get_inventory()->add(new Food("Apple", 1, 1, 1, 1, false));
$player1->get_inventory()->add(new Tool("Axe", 2, 2, 10, false));

$player2->get_inventory()->add(new Medicine("Health", 1, 2, 10));
$player2->get_inventory()->add(new Drink("Water", 2, 2, 5, 20, 300));
$player2->get_inventory()->add(new Tool("Sword", 2, 2, 25, false));

$player1->move_to_hand(0, 0);
$player1->move_to_hand(0, 1);

$player2->move_to_hand(0, 0);
$player2->move_to_hand(0, 1);

$player1->get_right_object()->attack($player2);
$player1->get_right_object()->attack($player2);

$player2->drink();
$player2->drink();
$player2->drink();

$player2->move_to_inventory(false);
$player2->move_to_hand(0, 2);
$player2->get_right_object()->attack($player1);

$player1->eat();
$player1->eat();

echo $player1 . "<br>" . $player2;
?>