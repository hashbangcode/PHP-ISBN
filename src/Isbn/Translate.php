<?php

namespace Isbn\Isbn;

class Translate {
    
    private $checkDigit;
    
    //public function __construct(CheckDigit $checkDigit) {
      //  $this->checkDigit = $checkDigit;
    //}

    public function to10($isbn)
    {
        if (strlen($isbn) > 13) {
            $isbn = substr($isbn, 4, -1);
        } else {
            $isbn = substr($isbn, 3, -1);
        }
        
        return $isbn . $this->make($isbn);
    }

    public function to13($isbn)
    {
        $isbn = substr($isbn, 0, -1);
        if (strlen($isbn) > 9) {
            $isbn = "978-" . $isbn;
        } else {
            $isbn = "978" . $isbn;
        }
        
        return $isbn . $this->make($isbn);
    }


    public function make($isbn)
    {
        //$isbn = $this->hyphens->removeHyphens($isbn);
        if (strlen($isbn) == 12 || strlen($isbn) == 13) {
            return $this->make13($isbn);
        }
        
        if (strlen($isbn) == 9 || strlen($isbn) == 10) {
            return $this->make10($isbn);
        }
        
        return false;
    }

    public function make10($isbn)
    {
        if (strlen($isbn) < 9 || strlen($isbn) > 10) {
          return false;
        }
        
        $check = 0;
        for ($i = 0; $i < 9; $i++) {
            if ($isbn[$i] == "X") {
                $check += 10 * intval(10 - $i);
            } else {
                $check += intval($isbn[$i]) * intval(10 - $i);
            }
        }
        
        $check = 11 - $check % 11;
        if ($check == 10) {
            return 'X';
        } elseif ($check == 11) {
            return 0;
        } else {
            return $check;
        }
    }

    public function make13($isbn)
    {
        if (strlen($isbn) < 12 || strlen($isbn) > 13) {
            return false;
        }
        
        $check = 0;
        for ($i = 0; $i < 12; $i+=2) {
            $check += substr($isbn, $i, 1);
        }
        
        for ($i = 1; $i < 12; $i+=2) {
            $check += 3 * substr($isbn, $i, 1);
        }
        
        $check = 10 - $check % 10;
        if ($check == 10) {
            return 0;
        } else {
            return $check;
        }
    }    
    
}