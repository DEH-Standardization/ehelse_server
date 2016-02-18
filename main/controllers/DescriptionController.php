<?php

class DescriptionController implements iController
{
    public function getResponse()
    {
        return "<pre>
@@  @@  @@@@@  @@     @@      @@@@@
@@  @@  @@     @@     @@     @@   @@
@@@@@@  @@@@@  @@     @@     @@   @@
@@  @@  @@     @@     @@     @@   @@
@@  @@  @@@@@  @@@@@  @@@@@   @@@@@

I am an API description!
Since you visit me it very well be the case you wrote something wrong in the url... :(

'destination'/version/standards/
ex: somehost/v1/standards
This returns all the standards!</pre>";
    }
}