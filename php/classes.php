<?php
// METHODS
function array2string (array $array) : string {
    $copy = $array;
    foreach ($copy as &$element) {
        if (is_array($element)) {
            $element = array2string($element);
        } else if ($element == null) {
            $element = "null";
        }
    }

    return "[" . implode(", ", $copy) . "]";
}

// INTERFACES   
interface SalleGaming {
    public function get_name() : string;
    public function set_name (string $value) : void;
    public function __toString() : string;
}

// CLASSES
abstract class Item implements SalleGaming {
    protected $name, $size, $uses;

    protected function __construct (string $name, int $size, int $uses) {
        $this->name = $name;
        $this->size = $size;
        $this->uses = $uses;
    }

    // ABSTRACT
    public abstract function use () : bool;

    // IMPLEMENT
    public function get_name() : string {
        return $this->name;
    }

    public function set_name (string $value) : void {
        $this->name = $value;
    }

    // GETTERS
    public function get_size () : int {
        return $this->size;
    }

    public function get_uses () : int {
        return $this->uses;
    }

    // SETTERS
    public function set_size (int $size) : void {
        $this->size = $size;
    }

    public function set_uses (int $uses) : void {
        $this->uses = $uses;
    }
}

class Expandable extends Item {
    protected function __construct (string $name, int $size, int $uses) {
        parent::__construct($name, $size, $uses);
    }

    // IMPLEMENT
    public function use () : bool {
        if ($this->uses > 0) {
            $this->uses--;
            return true;
        }

        return false;
    }

    public function __toString(): string {
        return "Expandable {{$this->name}, {$this->size}, {$this->uses}}";
    }
}

class Food extends Expandable {
    private $health_up, $food_up, $meat;

    public function __construct (string $name, int $size, int $uses, int $health_up, int $food_up, bool $meat) {
        parent::__construct($name, $size, $uses);

        $this->health_up = $health_up;
        $this->food_up = $food_up;
        $this->meat = $meat;
    }

    // GETTERS
    public function get_health_up () : int {
        return $this->health_up;
    }

    public function get_food_up () : int {
        return $this->food_up;
    }

    public function is_meat () : bool {
        return $this->meat;
    }

    public function is_plant () : bool {
        return !$this->meat;
    }

    // SETTERS
    public function set_health_up (int $health_up) : void {
        $this->health_up = $health_up;
    }

    public function set_food_up (int $food_up) : void {
        $this->food_up = $food_up;
    }

    public function set_type (bool $meat) : void {
        $this->meat = $meat;
    }
}

class Drink extends Expandable {
    const MAX_QUANTITY = 250;
    private $health_up, $drink_up, $quantity;

    public function __construct (string $name, int $size, int $uses, int $health_up, int $drink_up, int $quantity) {
        parent::__construct($name, $size, $uses);

        $this->health_up = $health_up;
        $this->drink_up = $drink_up;
        $this->quantity = $quantity;
    }

    // GETTERS
    public function get_health_up () : int {
        return $this->health_up;
    }

    public function get_drink_up () : int {
        return $this->drink_up;
    }

    public function get_quantity () : int {
        return $this->quantity;
    }

    // SETTERS
    public function set_health_up (int $health_up) : void {
        $this->health_up = $health_up;
    }

    public function set_drink_up (int $drink_up) : void {
        $this->drink_up = $drink_up;
    }

    public function set_quantity (int $quantity) : void {
        $this->quantity = $quantity;
    }

    // METHODS
    public function refill () : void {
        $this->set_quantity(self::MAX_QUANTITY);
    }
}

class Medicine extends Expandable {
    private $health_up;

    public function __construct (string $name, int $size, int $uses, int $health_up) {
        parent::__construct($name, $size, $uses);
        $this->health_up = $health_up;
    }

    // GETTERS
    public function get_health_up () : int {
        return $this->health_up;
    }

    // SETTERS
    public function set_health_up (int $health_up) : void {
        $this->health_up = $health_up;
    }
}

class Tool extends Item {
    private $max_uses, $harm, $broken;

    public function __construct (string $name, int $size, int $max_uses, int $harm, bool $broken) {
        parent::__construct($name, $size, $max_uses);
        
        $this->max_uses = $max_uses;
        $this->harm = $harm;
        $this->broken = $broken;
    }

    // IMPLEMENTS
    public function use () : bool {
        if ($this->uses < 0) {
            return false;
        }

        $this->uses--;
        if ($this->uses <= 0) {
            $this->set_broken(true);
        }

        return true;
    }

    public function __toString(): string {
        return "Tool {{$this->name}, {$this->size}, {$this->max_uses}, {$this->harm}, {$this->broken}}";
    }

    // GETTERS
    public function get_max_uses () : int {
        return $this->max_uses;
    }

    public function get_harm () : int {
        return $this->harm;
    }

    public function is_broken () : bool {
        return $this->broken;
    }

    // SETTERS
    public function set_max_uses (int $max_uses) : void {
        $this->max_uses = $max_uses;
    }

    public function set_harm (int $harm) : void {
        $this->harm = $harm;
    }

    public function set_broken (bool $broken) : void {
        $this->broken = $broken;
    }

    // METHODS
    public function attack (Player $player) : void {
        if (!$this->is_broken() && rand(0, 100) <= 75) {
            $player->injury($this->get_harm());
        }

        $this->use();
    }

    public function repair () {
        $this->set_uses($this->get_max_uses());
        $this->set_max_uses($this->get_max_uses() >> 1);
        $this->set_broken(false);
    }
}

final class Inventory implements SalleGaming {
    private $items;

    public function __construct (int $max_x, int $max_y) {
        $this->items = array_fill(0, $max_x, array_fill(0, $max_y, null));
    }

    // IMPLEMENTS
    public function get_name () : string {
        return "Inventory";
    }

    public function set_name (string $name) : void {
        throw new Error("Unsuported operation exception");
    }

    public function __toString() {
        return array2string($this->items);
    }

    // METHODS
    public function add (Item $item) : bool {
        $pos = $this->get_first_null();
        if ($pos[0] <= -1 || $pos[1] <= -1) {
            return false;
        }

        $this->items[$pos[0]][$pos[1]] = $item;
        return true;
    }

    public function get (int $x, int $y) : ?Item {
        return $this->items[$x][$y];
    }
    
    public function remove (Item $item) : bool {
        $pos = $this->get_position($item);
        if ($pos[0] <= -1 || $pos[1] <= -1) {
            return false;
        }

        $this->items[$pos[0]][$pos[1]] = null;
        return true;
    }

    public function contains (Item $item) : bool {
        $GLOBALS["item"] = $item;

        $this->forEach(function($i) {
            if ($i == $GLOBALS["item"]){
                return true;
            }
        });

        return false;
    }

    public function clear () : void {
        $this->items = array_fill(0, count($this->items), array_fill(0, count($this->items[0]), null));
    }

    public function reshape () {
        $GLOBALS["byType"] = [];

        $this->forEach(function ($item) {
            if ($item == null) {
                return;
            }

            $type = get_class($item);
            if (array_key_exists($type, $GLOBALS["byType"])) {
                array_push($GLOBALS["byType"][$type], $item);
            } else {
                $GLOBALS["byType"][$type] = [$item];
            }
        });

        $this->clear();
        foreach ($GLOBALS["byType"] as $key => $elements) {
            foreach ($elements as $element) {
                $this->add($element);
            }
        }
    }

    public function forEach (callable $callback) : void {
        for ($i=0;$i<count($this->items);$i++) {
            for ($j=0;$j<count($this->items[$i]);$j++) {
                $callback($this->items[$i][$j]);
            }
        }
    }

    // PRIVATE FUNCTIONS
    private function get_first_null () : array {
        for ($i=0;$i<count($this->items);$i++) {
            for ($j=0;$j<count($this->items[$i]);$j++) {
                if ($this->items[$i][$j] == null) {
                    return [$i, $j];
                }
            }
        }

        return [-1, -1];
    }

    private function get_position (Item $item) : array {
        for ($i=0;$i<count($this->items);$i++) {
            for ($j=0;$j<count($this->items[$i]);$j++) {
                if ($this->items[$i][$j] == $item) {
                    return [$i, $j];
                }
            }
        }

        return [-1, -1];
    }
}

class Player implements SalleGaming {
    const MAX_HEALTH = 100;
    const MAX_DRINK = 500;
    const MAX_FOOD = 500;

    private $health, $food, $drink, $inventory, $rightObject, $leftObject;

    public function __construct () {
        $this->health = self::MAX_HEALTH;
        $this->food = self::MAX_FOOD;
        $this->drink = self::MAX_DRINK;
        $this->inventory = new Inventory(5, 5);
        $this->rightObject = null;
        $this->leftObject = null;
    }

    // IMPLEMENT
    public function get_name (): string {
        return "Player";
    }

    public function set_name (string $name): void {
        throw new Error("Unsuported operation");
    }

    public function __toString(): string {
        return "Player {{$this->health}, {$this->food}, {$this->drink}, {$this->inventory}, {$this->rightObject}, {$this->leftObject}";
    }

    // GETTERS
    public function get_health () : int {
        return $this->health;
    }

    public function get_food () : int {
        return $this->food;
    }

    public function get_drink () : int {
        return $this->drink;
    }

    public function get_inventory () : Inventory {
        return $this->inventory;
    }

    public function get_left_object () : ?Item {
        return $this->leftObject;
    }

    public function get_right_object () : ?Item {
        return $this->rightObject;
    }

    // METHODS
    public function swap_hands () : void {
        $left = $this->leftObject;

        $this->leftObject = $this->rightObject;
        $this->rightObject = $left;
    }

    public function drink () : bool {
        if ($this->rightObject instanceof Drink && $this->rightObject->use()) {
            $this->drink = min(self::MAX_DRINK, $this->drink + $this->rightObject->get_drink_up());
            $this->health = min(self::MAX_HEALTH, $this->health + $this->rightObject->get_health_up());
            return true;
        } else if ($this->leftObject instanceof Drink && $this->leftObject->use()) {
            $this->drink = min(self::MAX_DRINK, $this->drink + $this->leftObject->get_drink_up());
            $this->health = min(self::MAX_HEALTH, $this->health + $this->leftObject->get_health_up());
            $this->leftObject->use();
            return true;
        }

        return false;
    }

    public function eat () : bool {
        if ($this->rightObject instanceof Food && $this->rightObject->use()) {
            $this->food = min(self::MAX_FOOD, $this->food + ($this->rightObject->is_meat() ? 2 : 1) * $this->rightObject->get_food_up());
            $this->health = min(self::MAX_HEALTH, $this->health + $this->rightObject->get_health_up());
            
            $this->inventory->remove($this->rightObject);
            $this->rightObject = null;
            return true;
        } else if ($this->leftObject instanceof Food && $this->leftObject->use()) {
            $this->food = min(self::MAX_FOOD, $this->food + ($this->leftObject->is_meat() ? 2 : 1) * $this->leftObject->get_food_up());
            $this->health = min(self::MAX_HEALTH, $this->health + $this->leftObject->get_health_up());
            
            $this->inventory->remove($this->leftObject);
            $this->leftObject = null;
            return true;
        }

        return false;
    }

    public function takeMedicine () : bool {
        if ($this->rightObject instanceof Medicine && $this->rightObject->use()) {
            $this->health = min(self::MAX_HEALTH, $this->health + $this->rightObject->get_health_up());
            return true;
        } else if ($this->leftObject instanceof Medicine && $this->leftObject->use()) {
            $this->health = min(self::MAX_HEALTH, $this->health + $this->leftObject->get_health_up());
            return true;
        }

        return false;
    }

    public function injury (float $damage) : void {
        $this->health = max(0, $this->health - round($damage));
    }

    public function search_inventory (string $name) : int {
        $GLOBALS["name"] = $name;
        $GLOBALS["count"] = 0;

        $this->inventory->forEach(function($item) {
            if ($item->get_name() == $GLOBALS["name"]) {
                $GLOBALS["count"]++;
            }
        });

        return $GLOBALS["count"];
    }

    public function health_check () : void {
        $div = intdiv($this->health, 25);

        switch ($div) {
            case 0: echo "RIP"; break;
            case 1: echo "Critical"; break;
            case 2: echo "Regular"; break;
            case 3: echo "Good"; break;
            case 4: echo "Very good"; break;
            default: throw new Error("Unexpected error"); break;
        }
    }

    public function move_to_hand (int $x, int $y) : bool {
        $item = $this->inventory->get($x, $y);
        if ($item == null) {
            return false;
        }

        if ($this->leftObject == null) {
            $this->leftObject = $item;
            return $this->inventory->remove($item);
        } else if ($this->rightObject == null) {
            $this->rightObject = $item;
            return $this->inventory->remove($item);
        }

        echo "Hands full";
        return false;
    }

    public function move_to_inventory (bool $left) : bool {
        if ($left && $this->leftObject != null) {
            if ($this->inventory->add($this->leftObject)) {
                $this->leftObject = null;
                return true;
            } else {
                echo "Full inventory";
                return false;
            }
        } else if (!$left && $this->rightObject != null) {
            if ($this->inventory->add($this->rightObject)) {
                $this->rightObject = null;
                return true;
            } else {
                echo "Full inventory";
                return false;
            }
        }

        return false;
    }
}
?>