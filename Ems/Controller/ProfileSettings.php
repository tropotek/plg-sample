<?php
namespace Ems\Controller;

use Tk\Request;
use Tk\Form;
use Tk\Form\Event;
use Tk\Form\Field;
use App\Controller\Iface;
use Ems\Plugin;

/**
 * @author Michael Mifsud <info@tropotek.com>
 * @link http://www.tropotek.com/
 * @license Copyright 2015 Michael Mifsud
 */
class ProfileSettings extends Iface
{

    /**
     * @var Form
     */
    protected $form = null;

    /**
     * @var \Tk\Db\Data|null
     */
    protected $data = null;

    /**
     * @var \App\Db\Profile
     */
    private $profile = null;


    /**
     *
     */
    public function __construct()
    {
        $this->setPageTitle('Sample Plugin - Course Profile Settings');
    }

    /**
     * doDefault
     *
     * @param Request $request
     */
    public function doDefault(Request $request)
    {
        /** @var Plugin $plugin */
        $plugin = Plugin::getInstance();

        $this->profile = \App\Db\ProfileMap::create()->find($request->get('zoneId'));
        $this->data = \Tk\Db\Data::create($plugin->getName() . '.course.profile', $this->profile->getId());

        $this->form = \App\Factory::createForm('formEdit');
        $this->form->setParam('renderer', \App\Factory::createFormRenderer($this->form));

        $this->form->addField(new Field\Input('plugin.title'))->setLabel('Site Title')->setRequired(true);
        $this->form->addField(new Field\Input('plugin.email'))->setLabel('Site Email')->setRequired(true);
        
        $this->form->addField(new Event\Button('update', array($this, 'doSubmit')));
        $this->form->addField(new Event\Button('save', array($this, 'doSubmit')));
        $this->form->addField(new Event\LinkButton('cancel', \App\Factory::getCrumbs()->getBackUrl()));

        $this->form->load($this->data->toArray());
        $this->form->execute();

    }

    /**
     * doSubmit()
     *
     * @param Form $form
     */
    public function doSubmit($form)
    {
        $values = $form->getValues();
        $this->data->replace($values);
        
        if (empty($values['plugin.title']) || strlen($values['plugin.title']) < 3) {
            $form->addFieldError('plugin.title', 'Please enter your name');
        }
        if (empty($values['plugin.email']) || !filter_var($values['plugin.email'], \FILTER_VALIDATE_EMAIL)) {
            $form->addFieldError('plugin.email', 'Please enter a valid email address');
        }
        
        if ($this->form->hasErrors()) {
            return;
        }
        
        $this->data->save();
        
        \Tk\Alert::addSuccess('Settings saved.');
        if ($form->getTriggeredEvent()->getName() == 'update') {
            \App\Uri::createHomeUrl('/course/profilePlugins.html')->set('profileId', $this->profile->getId())->redirect();
        }
        \Tk\Uri::create()->redirect();
    }

    /**
     * show()
     *
     * @return \Dom\Template
     */
    public function show()
    {
        $template = parent::show();
        
        // Render the form
        $template->insertTemplate($this->form->getId(), $this->form->getParam('renderer')->show()->getTemplate());

        return $template;
    }

    /**
     * DomTemplate magic method
     *
     * @return \Dom\Template
     */
    public function __makeTemplate()
    {
        $xhtml = <<<XHTML
<div var="content">

    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-cogs fa-fw"></i> Actions</div>
      <div class="panel-body " var="action-panel">
        <a href="javascript: window.history.back();" class="btn btn-default"><i class="fa fa-arrow-left"></i> <span>Back</span></a>
      </div>
    </div>
  
    <div class="panel panel-default">
      <div class="panel-heading"><i class="fa fa-cog"></i> Settings</div>
      <div class="panel-body">
        <div var="formEdit"></div>
      </div>
    </div>
    
</div>
XHTML;

        return \Dom\Loader::load($xhtml);
    }
}