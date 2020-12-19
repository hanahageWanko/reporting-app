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

  public function returnSuccess() {
    return $this->message['success'];
  }

  public function returnFailure() {
    return $this->message['failure'];
  }

  public function returnEmptyField() {
    return $this->message['empty_field'];
  }

  public function returnMissingField() {
    return $this->message['missing_field'];
  }

  public function returnInvlidId() {
    return $this->message['invlid_id'];
  }

  public function returnNoPostFound() {
    return $this->message['no_post_found'];
  }

}
