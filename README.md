# Phery Kohana

Kohana module with tight integration for Phery.js library

Working with AJAX (including file uploads) never been simpler. Just add the `phery.js` (like `<?php echo HTML::script('phery.js'); ?>`)
file on your page (or if you are using an assets manager, add `PHERY_JS` constant to the bunch), and instead of using `Controller`, use
`Phery_Controller` and instead of `Controller_Template` use `Phery_Template`.

Also, you should notice that the `$ajax` variable is available in ALL views (but you shouldn't do logic in your views though).
This is mainly useful because you need to do a `<?php echo $ajax->csrf; ?>` in your head, so you can install CSRF that comes enabled
by default, otherwise your AJAX calls will fail (CSRF stands for `Cross Site Request Forgery` to avoid remote attacks to your AJAX
forms and/or links)

##

For a full documentation of the phery.js library, check [https://github.com/pocesar/phery](https://github.com/pocesar/phery)

## Just follow these conventions:

When you want to make a PHP function available for just one action, you namespace it:

```php
class Controller_Index extends Phery_Template {

    /* This will be available for action_index only */
    public function ajax_index_edit()
    {
        return PheryResponse::factory()->alert('works for index!');
    }

    public function action_index()
    {
        $form = array(Phery::form_for('', 'edit'));
        $form[] = Form::input('id', 1, array('type' => 'hidden'));
        $form[] = Form::submit('submit', 'Send');
        $form[] = Form::close();
        $this->template->content = join('', $form);
    }

    public function action_view()
    {
        // calling phery.remote('edit') from the page won't call the phery_index_edit function
    }
}
```

When you want to reuse a callback for all actions, just don't namespace it:

```php
class Controller_Index extends Phery_Template {

    /* This will be available for action_index and action_view */
    public function ajax_edit()
    {
        $r = new PheryResponse;
        /* If you must, check which action the current call should deal */

        switch ($this->request->action()):
            case 'index':
                // do according to index
                $r->alert('its for index!');
                break;
            case 'view':
                // do according to view
                $r->alert('its for view!');
                break;
        endswitch;

        return $r;
    }

    public function action_index()
    {
        $form = array(Phery::form_for('', 'edit'));
        $form[] = Form::input('id', 1, array('type' => 'hidden'));
        $form[] = Form::submit('submit', 'Send');
        $form[] = Form::close();
        $this->template->content = join('', $form);
    }

    public function action_view()
    {
        $this->template->content = Phery::link_to('Click me', 'edit', array('args' => array('id' => 1)));
    }
}
```

You may change the AJAX options per controller, by passing configuration to the `ajax` function inside your controller:

```php
class Controller_Index extends Phery_Controller {

    public function ajax()
    {
        return array(
            'error_reporting' => E_ALL
        );
    }

    public function action_index()
    {
    }
}
```

Your AJAX function will be processed AFTER your `after()` function, so you can place all the transformations, cleanups,
in there.

Also, inside the `Phery_Controller` or `Phery_Template`, there is the same `$ajax` variable, so you can mess with `Phery` youself:

```php
class Controller_Index extends Phery_Controller {

    function action_index()
    {
        $this->ajax->set(array(
            'edit' => 'Helper::staticAjaxMethod',
            'load' => array(new Model_User, 'load')
        ));
    }
}
```

This make it highly flexible, so you don't need to be tied to convention.

If any exception happens when using AJAX, it will go 'silently' to your `application/logs` and it will fail with an
empty JSON response `{}`

## Coming soon

* Render view partial views out of the box (without having to do `$this->ajax->render_view`
