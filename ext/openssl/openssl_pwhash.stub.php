<?php

/** @generate-class-entries */

#if defined(HAVE_OPENSSL_ARGON2)
/**
 * @var string
 */
const PASSWORD_ARGON2I = "argon2i";
/**
 * @var string
 */
const PASSWORD_ARGON2ID = "argon2id";
/**
 * @var int
 * @cvalue PHP_OPENSSL_PWHASH_MEMLIMIT
 */
const PASSWORD_ARGON2_DEFAULT_MEMORY_COST = UNKNOWN;
/**
 * @var int
 * @cvalue PHP_OPENSSL_PWHASH_ITERLIMIT
 */
const PASSWORD_ARGON2_DEFAULT_TIME_COST = UNKNOWN;
/**
 * @var int
 * @cvalue PHP_OPENSSL_PWHASH_THREADS
 */
const PASSWORD_ARGON2_DEFAULT_THREADS = UNKNOWN;
/**
 * @var string
 */
const PASSWORD_ARGON2_PROVIDER = "openssl";
#endif

