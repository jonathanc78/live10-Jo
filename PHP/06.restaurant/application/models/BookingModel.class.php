<?php


class BookingModel {

    function add($customer_id, $date, $quantity) {
        $sql = "INSERT INTO bookings (Customer_Id, BookingDate, Quantity) VALUES (?,?,?)";
        $db = new Database();

        // si ça te fait plaiz
        return $db->executeSql($sql, [$customer_id, $date, $quantity]);
    }
}