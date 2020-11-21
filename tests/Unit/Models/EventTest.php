<?php

namespace Tests\Unit\Models;

use App\Enums\DocumentStatus as DocumentStatusEnum;
use App\Enums\DocumentType;
use App\Enums\EventStatus as EventStatusEnum;
use App\Models\Foodfleet\Document;
use App\Models\Foodfleet\Document\Template\Template;
use App\Models\Foodfleet\Event;
use App\Models\Foodfleet\EventHistory;
use App\Models\Foodfleet\Square\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    public function testModel()
    {
        $event = factory(Event::class)->create();

        $this->assertDatabaseHas('events', [
            "uuid" => $event->uuid,
            "name" => $event->name,
            "type_id" => $event->type_id,
            "status_id" => $event->status_id,
            "location_uuid" => $event->location_uuid,
            "start_at" => $event->start_at,
            "end_at" => $event->end_at,
            "staff_notes" => $event->staff_notes,
            "member_notes" => $event->member_notes,
            "customer_notes" => $event->customer_notes,
            "host_uuid" => $event->host_uuid,
            "host_status" => $event->host_status,
            "manager_uuid" => $event->manager_uuid,
            "budget" => $event->budget,
            "attendees" => $event->attendees,
            "commission_rate" => $event->commission_rate,
            "commission_type" => $event->commission_type,
            "created_at" => $event->created_at,
            "updated_at" => $event->updated_at,
            "venue_uuid" => $event->venue_uuid
        ]);

        // table relations
        $this->assertEquals($event->type_id, $event->type->id);
        $this->assertEquals($event->status_id, $event->status->id);
        $this->assertEquals($event->location_uuid, $event->location->uuid);
        $this->assertEquals($event->host_uuid, $event->host->uuid);
        $this->assertEquals($event->manager_uuid, $event->manager->uuid);
        $this->assertEquals($event->venue_uuid, $event->venue->uuid);

        // external table relations
        // TODO: transactions
        // TODO: eventTags
        // TODO: documents
        // TODO: menuItems
        // TODO: messages
        // TODO: schedule
        // TODO: histories
        $transaction = factory(Transaction::class)->create([
            'event_uuid' => $event->uuid
        ]);
        $this->assertEquals($transaction->uuid, $event->transactions->first()->uuid);
    }

    public function testObserverWhenEventCreated()
    {
        $event = factory(Event::class)->make([
            'status_id' => EventStatusEnum::DRAFT
        ]);
        $this->assertEquals(0, EventHistory::where([
            'event_uuid' => $event->uuid
        ])->count());

        $event->save();
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => EventStatusEnum::DRAFT
        ])->whereNotNull('date')->count());
    }

    public function testObserverWhenEventUpdatedWithSameStatus()
    {
        $event = factory(Event::class)->create();
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => $event->status_id
        ])->count());

        $event->update(array_merge(factory(Event::class)->make()->toArray(), [
            'status_id' => $event->status_id
        ]));
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
        ])->count());
    }

    public function testObserverWhenEventUpdatedWithDifferentStatus()
    {
        $event = factory(Event::class)->create([
            'status_id' => EventStatusEnum::FF_INITIAL_REVIEW
        ]);
        $this->assertEquals(1, EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => $event->status_id
        ])->count());

        $event->update([
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ]);
        $this->assertEquals(2, EventHistory::where([
            'event_uuid' => $event->uuid,
        ])->count());
        $history = EventHistory::where([
            'event_uuid' => $event->uuid,
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ])->first();
        $this->assertNotNull($history->date);
    }

    public function testObserverWhenEventDeleted()
    {
        $event = factory(Event::class)->create();
        for ($i = 1; $i <= 9; $i++) {
            factory(EventHistory::class)->create([
                'event_uuid' => $event->uuid,
                'status_id' => $i
            ]);
        }
        $this->assertEquals(10, EventHistory::where([
            'event_uuid' => $event->uuid
        ])->count());

        $event->delete();
        $this->assertEquals(0, EventHistory::where('event_uuid', $event->uuid)->count());
    }

    public function getCustomerAgreementStatusProvider()
    {
        return [
            [EventStatusEnum::DRAFT],
            [EventStatusEnum::FF_INITIAL_REVIEW],
            [EventStatusEnum::FLEET_MEMBER_SELECTION],
            [EventStatusEnum::CUSTOMER_REVIEW],
            [EventStatusEnum::FLEET_MEMBER_CONTRACTS],
            [EventStatusEnum::CONFIRMED],
            [EventStatusEnum::CANCELLED],
            [EventStatusEnum::PAST],
        ];
    }

    /**
     * @param $status_id
     * @dataProvider getCustomerAgreementStatusProvider
     */
    public function testObserverWhenEventUpdateWithStatusDifferentOfCustomerAgreement($status_id)
    {
        $event = factory(Event::class)->create([
            'status_id' => 999
        ]);
        $template = Template::getClientAgreement();
        $count = Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count();
        $event->update([
            'status_id' => $status_id
        ]);
        // count remain the same
        $this->assertEquals($count, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());
    }

    /**
     * @param $status_id
     * @dataProvider getCustomerAgreementStatusProvider
     */
    public function testObserverWhenEventUpdatedWithStatusCustomerAgreement($status_id)
    {
        $event = factory(Event::class)->create([
            'status_id' => 999
        ]);
        $template = Template::getClientAgreement();
        $count = Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count();
        $event->update([
            'status_id' => $status_id
        ]);
        $this->assertEquals($count, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());

        $event->update([
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ]);
        $this->assertEquals($count + 1, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());
        $this->assertDatabaseHas('documents', [
            'status_id' => DocumentStatusEnum::PENDING,
            'type_id' => DocumentType::FROM_TEMPLATE,
            'title' => $event->name.' - Customer Agreement',
            'description' => $event->name.' - Customer Agreement',
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ]);
    }

    /**
     * @param $status_id
     * @dataProvider getCustomerAgreementStatusProvider
     */
    public function testEventStatusChangedToCustomerAgreementTwice($status_id)
    {
        $event = factory(Event::class)->create([
            'status_id' => 999
        ]);
        $template = Template::getClientAgreement();
        $count = Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count();
        $event->update([
            'status_id' => $status_id
        ]);
        $this->assertEquals($count, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());

        $event->update([
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ]);
        $this->assertEquals($count + 1, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());

        // change back to previous status and back to the wanted one
        $event->update([
            'status_id' => $status_id
        ]);
        $event->update([
            'status_id' => EventStatusEnum::CUSTOMER_AGREEMENT
        ]);

        // should be unchanged
        $this->assertEquals($count + 1, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());
    }

    public function getFleetMemberEventStatusProvider()
    {
        return [
            [EventStatusEnum::DRAFT],
            [EventStatusEnum::FF_INITIAL_REVIEW],
            [EventStatusEnum::CUSTOMER_AGREEMENT],
            [EventStatusEnum::FLEET_MEMBER_SELECTION],
            [EventStatusEnum::CUSTOMER_REVIEW],
            [EventStatusEnum::CONFIRMED],
            [EventStatusEnum::CANCELLED],
            [EventStatusEnum::PAST],
        ];
    }

    /**
     * @param $status_id
     * @dataProvider getCustomerAgreementStatusProvider
     */
    public function testObserverWhenEventUpdateWithStatusDifferentOfFleetMemberEventContract($status_id)
    {
        $event = factory(Event::class)->create([
            'status_id' => 999
        ]);
        $template = Template::getFleetMemberEventContract();
        $count = Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count();

        $event->update([
            'status_id' => $status_id
        ]);
        // count remain the same
        $this->assertEquals($count, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());
    }

    /**
     * @param $status_id
     * @dataProvider getCustomerAgreementStatusProvider
     */
    public function testObserverWhenEventUpdatedWithStatusFleetMemberEventContract($status_id)
    {
        $event = factory(Event::class)->create([
            'status_id' => 999
        ]);
        $template = Template::getFleetMemberEventContract();
        $count = Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count();
        $event->update([
            'status_id' => $status_id
        ]);
        $this->assertEquals($count, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());

        $event->update([
            'status_id' => EventStatusEnum::FLEET_MEMBER_CONTRACTS
        ]);
        $this->assertEquals($count + 1, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());
        $this->assertDatabaseHas('documents', [
            'status_id' => DocumentStatusEnum::PENDING,
            'type_id' => DocumentType::FROM_TEMPLATE,
            'title' => $event->name.' - Fleet member contract',
            'description' => $event->name.' - Fleet member contract',
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ]);
    }

    /**
     * @param $status_id
     * @dataProvider getFleetMemberEventStatusProvider
     */
    public function testEventStatusChangedToFleetMemberEventContractTwice($status_id)
    {
        $event = factory(Event::class)->create([
            'status_id' => 999
        ]);
        $template = Template::getFleetMemberEventContract();
        $count = Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count();
        $event->update([
            'status_id' => $status_id
        ]);
        $this->assertEquals($count, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());

        $event->update([
            'status_id' => EventStatusEnum::FLEET_MEMBER_CONTRACTS
        ]);
        $this->assertEquals($count + 1, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());

        // change back to previous status and back to the wanted one
        $event->update([
            'status_id' => $status_id
        ]);
        $event->update([
            'status_id' => EventStatusEnum::FLEET_MEMBER_CONTRACTS
        ]);

        // should be unchanged
        $this->assertEquals($count + 1, Document::where([
            'assigned_uuid' => $event->uuid,
            'template_uuid' => $template->uuid
        ])->count());
    }
}
