<?php

// DATABASE

const SERVER = "localhost";
const DB = "biblioteca_publica";
const USER = "root";
const PASS = "";

const SGBD = 'mysql:host='.SERVER.';dbname='.DB;

// ENCRIPTACION. No cambiar si ya hemos usado la encriptacion

const METHOD = 'AES-256-CBC';
const SECRET_KEY = '$proyecto@mvc@2019$';
const SECRET_IV = '02082019';