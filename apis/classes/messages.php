<?php
class Messages
{
  public $message = [
      'success' => 'Data changed Successfully',
      'failure' => 'Data not changed',
      'empty_field' => 'Oops! empty field detected',
      'missing_field' => 'Please fill all the fields ',
      'invlid_id' => 'invlid ID',
      'no_post_found' => 'No post found'
    ];

  public function Success() {
    return $this->message['success'];
  }

  public function Failure() {
    return $this->message['failure'];
  }

  public function EmptyField() {
    return $this->message['empty_field'];
  }

  public function MissingField() {
    return $this->message['missing_field'];
  }

  public function InvlidId() {
    return $this->message['invlid_id'];
  }

  public function NoPostFound() {
    return $this->message['no_post_found'];
  }

}
