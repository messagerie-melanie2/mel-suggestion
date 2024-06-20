<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Abstract test case class.
 *
 * This class serves as the base test case class for all test classes in the application.
 * It extends Laravel's base test case class and includes functionality for creating the application.
 *
 * @package Tests
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
}
