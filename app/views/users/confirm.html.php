<?php
?>
<h4>Your email is verified!</h4>
Thank you for verifying your email address. We will also be verifying your mobile number.
<?=$this->form->create("",array('url'=>'/users/mobile/')); ?>
<?=$this->form->field('mobile', array('label'=>'Mobile','placeholder'=>'+91 1112223333' )); ?>
<?=$this->form->field('user_id', array('type'=>'hidden','value'=>$id )); ?>
<?=$this->form->submit('Send SMS'); ?>
<?=$this->form->end(); ?>

We will send you an SMS to the number you provide, please confirm the same number on the next screen.