<?php

namespace App\Services;

use Illuminate\Http\Response;

class ResponseManipulation
{
  private $tag;
  private $content;
  private $response;
  private $position;

  public function __construct($tag = '</head>', $content = '', Response $response)
  {
    $this->tag = $tag;
    $this->content = $content;
    $this->response = $response;
    $this->position = strripos($this->response->getContent(), $this->tag);
  }

  public function getResponse()
  {
    // Skip if the tag not found
    if (!$this->position) {
      return $this->response;
    }

    $response_content = $this->response->getContent();

    $content = ''
      . substr($response_content, 0, $this->position)
      . $this->content
      . substr($response_content, $this->position);

    return $this->response->setContent($content);
  }
}
