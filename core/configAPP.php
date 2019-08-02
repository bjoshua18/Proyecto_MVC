<?php

// DATABASE

const SERVER = "";
const DB = "";
const USER = "";
const PASS = "";

const SGBD = 'mysql:host='.SERVER.';dbname='.DB;

// ENCRIPTACION. No cambiar si ya hemos usado la encriptacion

const METHOD = 'AES-256-CBC';
const SECRET_KEY = '$proyecto@mvc@2019$';
const SECRET_IV = '02082019';