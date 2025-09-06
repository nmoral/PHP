<?php

namespace Session00;

/**
 * Classe Calculator avec des méthodes à tester
 * 
 * Cette classe contient des méthodes simples mais qui nécessitent
 * des tests complets pour valider leur comportement.
 * 
 * 🎯 OBJECTIF : Apprendre à écrire des tests unitaires avec PHPUnit
 */
class Calculator
{
    /**
     * Additionne deux nombres
     * 
     * @param float $a Premier nombre
     * @param float $b Deuxième nombre
     * @return float Résultat de l'addition
     */
    public function add(float $a, float $b): float
    {
        return $a + $b;
    }
    
    /**
     * Divise deux nombres
     * 
     * @param float $a Dividende
     * @param float $b Diviseur
     * @return float Résultat de la division
     * @throws DivisionByZeroError Si le diviseur est zéro
     */
    public function divide(float $a, float $b): float
    {
        if ($b === 0.0) {
            throw new DivisionByZeroError("Division by zero is not allowed");
        }
        
        return $a / $b;
    }
    
    /**
     * Calcule une remise sur un prix
     * 
     * @param float $price Prix original
     * @param float $discountPercent Pourcentage de remise (0-100)
     * @return float Prix après remise
     * @throws InvalidArgumentException Si le prix est négatif ou si la remise est invalide
     */
    public function calculateDiscount(float $price, float $discountPercent): float
    {
        if ($price < 0) {
            throw new InvalidArgumentException("Price cannot be negative");
        }
        
        if ($discountPercent < 0 || $discountPercent > 100) {
            throw new InvalidArgumentException("Discount must be between 0 and 100");
        }
        
        return $price * (1 - $discountPercent / 100);
    }
    
    /**
     * Calcule la moyenne de plusieurs nombres
     * 
     * @param array $numbers Tableau de nombres
     * @return float Moyenne des nombres
     * @throws InvalidArgumentException Si le tableau est vide
     */
    public function calculateAverage(array $numbers): float
    {
        if (empty($numbers)) {
            throw new InvalidArgumentException("Cannot calculate average of empty array");
        }
        
        $sum = array_sum($numbers);
        $count = count($numbers);
        
        return $sum / $count;
    }
    
    /**
     * Trouve le nombre maximum dans un tableau
     * 
     * @param array $numbers Tableau de nombres
     * @return float Nombre maximum
     * @throws InvalidArgumentException Si le tableau est vide
     */
    public function findMax(array $numbers): float
    {
        if (empty($numbers)) {
            throw new InvalidArgumentException("Cannot find max of empty array");
        }
        
        return max($numbers);
    }
    
    /**
     * Trouve le nombre minimum dans un tableau
     * 
     * @param array $numbers Tableau de nombres
     * @return float Nombre minimum
     * @throws InvalidArgumentException Si le tableau est vide
     */
    public function findMin(array $numbers): float
    {
        if (empty($numbers)) {
            throw new InvalidArgumentException("Cannot find min of empty array");
        }
        
        return min($numbers);
    }
    
    /**
     * Vérifie si un nombre est pair
     * 
     * @param int $number Nombre à vérifier
     * @return bool True si le nombre est pair, false sinon
     */
    public function isEven(int $number): bool
    {
        return $number % 2 === 0;
    }
    
    /**
     * Vérifie si un nombre est premier
     * 
     * @param int $number Nombre à vérifier
     * @return bool True si le nombre est premier, false sinon
     * @throws InvalidArgumentException Si le nombre est négatif
     */
    public function isPrime(int $number): bool
    {
        if ($number < 0) {
            throw new InvalidArgumentException("Cannot check if negative number is prime");
        }
        
        if ($number < 2) {
            return false;
        }
        
        for ($i = 2; $i <= sqrt($number); $i++) {
            if ($number % $i === 0) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Calcule la factorielle d'un nombre
     * 
     * @param int $number Nombre dont calculer la factorielle
     * @return int Factorielle du nombre
     * @throws InvalidArgumentException Si le nombre est négatif
     */
    public function factorial(int $number): int
    {
        if ($number < 0) {
            throw new InvalidArgumentException("Cannot calculate factorial of negative number");
        }
        
        if ($number === 0 || $number === 1) {
            return 1;
        }
        
        $result = 1;
        for ($i = 2; $i <= $number; $i++) {
            $result *= $i;
        }
        
        return $result;
    }
}
