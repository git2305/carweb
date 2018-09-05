<?php
App::uses('EmailTemplate', 'Model');
App::uses('CakeEmail', 'Network/Email');
class EmailComponent extends Component {
    public $Email;
    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $content;
    public $fromName;
    public $fromEmail;
    
    public function initialize(\Controller $controller) {
        parent::initialize($controller);
        $this->Email = new CakeEmail();
        $this->Email->config('development');
    }
    
    public function sendEmail( $autoEmailId = null, $emailData = array(), $attachment = '' ){
        
        $this->EmailTemplate = new EmailTemplate();
        $autoEmailData = $this->EmailTemplate->find('first', ['conditions'=> ['id'=> $autoEmailId ], 'recursive' => -1  ] );
        $this->content = $autoEmailData['EmailTemplate']['description'];
        $this->subject = $autoEmailData['EmailTemplate']['subject'];
        $this->to = $autoEmailData['EmailTemplate']['to'];
        $this->cc= $autoEmailData['EmailTemplate']['cc'];
        $this->bcc = $autoEmailData['EmailTemplate']['bcc'];
        $this->fromEmail = $autoEmailData['EmailTemplate']['from_email'];
        $this->fromName = $autoEmailData['EmailTemplate']['from_name'];
        
        $this->replaceEmailData( $emailData );
        $this->prepareRecipient( $emailData );
        
        $this->Email->subject($this->subject);
        $this->Email->emailFormat('both');
        $this->Email->to($this->to);
        if( $this->cc ){
            $this->Email->cc('panchal.aniket1@gmail.com');
        }
        
        if( $this->bcc ){
            $this->Email->bcc('panchal.aniket1@gmail.com');
        }
        
        $this->Email->from( [$this->fromEmail => $this->fromName] ); //'info@youmewebs.com'
        
        if( $attachment && $attachment != '' ){
            $this->Email->attachments($attachment);
        }
        
        //$this->Email->template('template');
        
        //pr($this->content); 
        try{
            return $data = $this->Email->send($this->content);
            //pr($data); die;
        } catch(Exception $e){
            echo $e->getMessage(); die;
        }  
    }
    
    
    public function contactEmail( $option = array())
    {  
        $this->content = $option['body'];
        $this->subject = $option['subject'];
        $this->to = 'panchal.aniket@gmail.com';       
        $this->fromEmail = 'info@ef24.ch';
        $this->fromName = $option['fromname']; 
        
        $this->Email->subject($this->subject);
        $this->Email->emailFormat('both');
        $this->Email->to($this->to);
         
        
        $this->Email->from( [$this->fromEmail => $this->fromName] ); //'info@youmewebs.com'
         
     
        try{
            $data = $this->Email->send($this->content);
            //pr($data); die;
        } catch(Exception $e){
            echo $e->getMessage(); die;
        }  
    }
    
    public function replaceEmailData( $emailData = array() ){
        if( !empty($emailData) ){
            foreach( $emailData as $key => $val ){
                $this->subject = str_replace($key, $val, $this->subject);
                $this->content = str_replace($key, $val, $this->content);
            }
        }
    }
    
    public function prepareRecipient($emailData = array()){
        if( !empty($emailData) ){
            foreach( $emailData as $key => $val ){
                if(is_array($val)  ){
                    $this->to = str_replace($key, implode(',', $val), $this->to);
                    $this->cc = str_replace($key, implode(',', $val), $this->cc);
                    $this->bcc = str_replace($key, implode(',', $val), $this->bcc);
                } else {
                    $this->to = str_replace($key, $val, $this->to);
                    $this->cc = str_replace($key, $val, $this->cc);
                    $this->bcc = str_replace($key, $val, $this->bcc);
                }
                
                
            }
        }
    }
    
}