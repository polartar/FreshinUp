<?php

namespace Tests\Unit\Models;

use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Document\Template\Template;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TemplateTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {

        /** @var Template $template */
        $template = factory(Template::class)->create();

        $this->assertDatabaseHas('document_templates', [
            'uuid' => $template->uuid
        ]);

        // relations
        $this->assertEquals($template->status_id, $template->status->id);
        $document = factory(Document::class)->create([
            'template_uuid' => $template->uuid
        ]);
        $this->assertEquals($document->uuid, $template->documents->first()->uuid);
        $this->assertEquals($template->updated_by_uuid, $template->updatedBy->uuid);
    }

    public function testGetClientAgreementWhenNotExist()
    {
        $title = Template::CLIENT_EVENT_AGREEMENT;
        $this->assertEquals(0, Template::where('title', $title)->count());
        $template = Template::getClientAgreement();
        $this->assertEquals(1, Template::where('title', $title)->count());
        $this->assertNotNull($template->uuid);
        $this->assertEquals($title, $template->title);
    }

    public function testGetClientAgreementWhenExist()
    {
        $title = Template::CLIENT_EVENT_AGREEMENT;
        $oldTemplate = factory(Template::class)->create([
            'title' => $title,
        ]);
        $this->assertEquals(1, Template::where('title', $title)->count());
        $template = Template::getClientAgreement();
        $this->assertEquals(1, Template::where('title', $title)->count());
        $this->assertEquals($title, $template->title);
        $this->assertEquals($oldTemplate->uuid, $template->uuid);
    }

    public function testGetFleetMemberContractsWhenNotExist()
    {
        $title = Template::FLEET_MEMBER_EVENT_CONTRACT;
        $this->assertEquals(0, Template::where('title', $title)->count());
        $template = Template::getFleetMemberEventContract();
        $this->assertEquals(1, Template::where('title', $title)->count());
        $this->assertNotNull($template->uuid);
        $this->assertEquals($title, $template->title);
    }

    public function testGetFleetMemberContractsWhenExist()
    {
        $title = Template::FLEET_MEMBER_EVENT_CONTRACT;
        $oldTemplate = factory(Template::class)->create([
            'title' => $title,
        ]);
        $this->assertEquals(1, Template::where('title', $title)->count());
        $template = Template::getFleetMemberEventContract();
        $this->assertEquals(1, Template::where('title', $title)->count());
        $this->assertEquals($title, $template->title);
        $this->assertEquals($oldTemplate->uuid, $template->uuid);
    }


    public function testObserverWhenItemCreated()
    {
        $user = factory(User::class)->create();
        Auth::loginUsingId($user->id);
        $template = factory(Template::class)->create();
        $this->assertEquals($user->uuid, $template->updated_by_uuid);
    }

    public function testObserverWhenItemUpdated()
    {
        $john = factory(User::class)->create();
        Auth::loginUsingId($john->id);
        $template = factory(Template::class)->create();
        $this->assertEquals($john->uuid, $template->updated_by_uuid);

        $jane = factory(User::class)->create();
        Auth::loginUsingId($jane->id);
        $payload = factory(Template::class)->make()->toArray();
        $template->update($payload);
        $this->assertEquals($jane->uuid, $template->updated_by_uuid);
    }
}
