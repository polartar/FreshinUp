import { FIXTURE_USERS } from './users'

export const FIXTURE_DOCUMENT_TEMPLATES = [
  {
    id: 1,
    uuid: 'a039ca2e-f23f-3719-907b-a678a0f61712',
    title: 'catering contract',
    description: 'CHAPTER VIII. The Queen\'s argument was, that she looked up, but it did not quite sure',
    content: `
<h3>Catering Contract</h3>
      <p>
        This Catering Contract is entered into between 2 Brothers Kitchen (“Caterer”) and John Smith (“Client”) (together, “Parties”) and sets forth the agreement between the Parties relating to catering services to be provided by the Caterer for Client for the event identified in this Contract.
      </p>
      <h3>1. Event Details</h3>
      <p>
      Client is hiring Caterer to provide food and beverages, and related services, for the following event (“Event”):
      Date: June 12, 2019
      Event start time (for guests): 10:00 AM
      Event end time (for guests): 4:00 PM
      Location: LA Stadium (“Venue”)
      Estimated number of guests: 300
      </p>
      <h3>2. Menu to Be Served</h3>
      <p>
      The Parties have agreed to the menu attached to this Catering Agreement as Exhibit A. Caterer reserves the right to make small changes to the menu if key ingredients are unable to be sourced due to reasons beyond the control of the Parties. The following limitations will apply to this reservation of right - {{event.LimitationsOnMenuAlterations}}. No alcoholic beverages will be served without a separate agreement relating thereto.
      </p>
      <h3>3. Coordination with Venue</h3>
      <p>
      Caterer will need to have access to the Venue no later than {{event.PrepAdvanceTime}} hours in advance of the Start Time for the Event, and {{event.CleanUpTime}} hours after the End Time for clean up. Client will make all necessary arrangements, at Client’s expense, to get this access arranged.
      In exchange for the services of Caterer as specified in this Catering Contract, Client will pay to
      Caterer $ {{event.PerPersonCharge}} per person attending the event, but in no event less than the
      Guest Count provided by Client to Caterer one week in advance of the Event. As of the signing of
      this Contract, the total amount is estimated to be $ {{event.EstimatedTotalCost}} (“Estimated Total
      Cost”).
      Payment will be made to the Caterer as follows: $ {{event.Deposit}} deposit due on the date of signing, and the balance of approximately $ {{event.ApproximateSecondPayment}} will be due one week in advance of the event. The exact amount due will be determined, and provided from Client to Caterer in writing, one week in advance of the Event along with a Final Guest Count.
      </p>
      <h3>5. Responsibilities for Related Costs</h3>
      <p>
      Client is solely responsible for all costs and/or deposits relating to use of the Venue, and for obtaining any necessary permissions, authorizations, or other requirement of Caterer providing services at the Venue.
      </p>
      <h3>6. Insurance and Indemnification</h3>
      <p>
      Caterer has, or will obtain, general liability insurance relating to Caterer’s services at the Event.
      However, Client will indemnify and hold harmless Caterer for any damage, theft, or loss of Caterer’s
      property occurring at the event, causes by any of Client’s guests.
      </p>
      <h3>7. Cancellation</h3>
      <p>
      If the Client needs to cancel the event, Client must provide written notice to Caterer along with any
      required cancellation fee described in this Catering Contract, to effect cancellation.
      Client understands that upon entering into this Contract, Caterer is committing time and resources to
      this Event and thus cancellation would result in lost income and lost business opportunities in an
      amount hard to precisely calculate. Therefore, the following cancellation limitations will apply.
      If Client requests cancellation of this Contract 90 days or more before the Event, Caterer shall be
      entitled to {{event.PercentFor90DayCancellation}} percent of the Estimated Total Cost.
      If Client requests cancellation 45-89 days before the Event, Caterer shall be entitled to
      {{event.PercentFor45DayCancellation}} percent of the Estimated Total Costs.
      If Client requests cancellation 31-44 days before the Event, Caterer shall be entitled to
      {{event.PercentFor31DayCancellation}} .
      After 30 days in advance of the Event, Caterer shall be entitled to 100 percent of the Estimated Total
      Cost.
      The Client’s deposit will be credited against the cancellation fees owed. Any balance will be payable
      upon the notice of cancellation.
      </p>
      <h3>8. Legal Compliance</h3>
      <p>
      Caterer will work in compliance with all applicable local health department rules and regulations
      relating to food preparation and food service.
      </p>
      <h3>9. Assignment</h3>
      <p>
      This Contract cannot be assigned by either Party without the other’s written consent, with the
      exception set forth in paragraph 10, below.
      </p>
      <h3>10. Limitation of Remedies</h3>
      <p>
      If Caterer cannot fulfill its obligations under this Contract for reasons outside of its control, Caterer
      may locate and retain a replacement catering company at no additional cost to Client, or refund
      Client’s money in full. Caterer will not be responsible for any additional damages or compensation
      under these circumstances.
      </p>
      <h3>11. Resolution of Disputes</h3>
      <p>
      The Parties agree to not post any negative information about the other arising out of this Contract or
      Event on any online forum or website without providing advance written notice of the intended
      content thereof, and providing the other party with an opportunity to resolve any issues between the
      parties amicably.</p>
      <h3>12. Jurisdiction and Venue</h3>
      <p>
      This Contract will be interpreted according to the laws of the State of [State] and any legal action
      must be filed in the County of [County] in the State of [State] .</p>
      <h3>13. Entire Agreement</h3>
      <p>This document, along with its exhibits and attachments, constitutes the entire agreement between
      the Parties.</p>
`,
    status_id: 1,
    status: {
      id: 1,
      name: 'Published'
    },
    updated_by: FIXTURE_USERS[0],
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 2,
    uuid: '3409787a-cbce-3e9b-9b57-b6af75e65dc8',
    title: 'quia',
    status_id: 1,
    status: {
      id: 2,
      name: 'Published'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 3,
    uuid: '256d0221-b6c5-3414-ad7f-1b7642433c14',
    title: 'totam',
    status_id: 2,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 4,
    uuid: '5d539d39-43b1-32ed-aa53-9341408644e6',
    title: 'iste',
    status_id: 2,
    status: {
      id: 2,
      name: 'Published'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 5,
    uuid: '27491085-0176-3c17-a5e6-a5c9792abbd0',
    title: 'rerum',
    status_id: 1,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 6,
    uuid: 'dc57c632-c52c-3f48-98bc-b6a8e478d4ff',
    title: 'qui',
    status_id: 1,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 7,
    uuid: '6561e166-97d8-3f84-be70-10703de63cfd',
    title: 'sunt',
    status_id: 1,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 8,
    uuid: '8a647984-8c06-3a27-82e8-d64c094bf9f3',
    title: 'et',
    status_id: 1,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 9,
    uuid: '0b8e9315-571e-3b7e-998a-33958446c3d6',
    title: 'qui',
    status_id: 1,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  },
  {
    id: 10,
    uuid: '661eb703-86cf-3705-ba06-792eda55dfeb',
    title: 'adipisci',
    status_id: 2,
    status: {
      id: 1,
      name: 'Draft'
    },
    created_at: '2019-09-30T03:51:14.000000Z',
    updated_at: '2019-09-30T03:51:51.000000Z'
  }
]

export const FIXTURE_DOCUMENT_TEMPLATES_VARIABLES = {
  event: {
    LimitationsOnMenuAlterations: '100',
    PrepAdvanceTime: '100',
    CleanUpTime: '100',
    PerPersonCharge: '100',
    EstimatedTotalCost: '100',
    Deposit: '100',
    ApproximateSecondPayment: '100',
    PercentFor90DayCancellation: '100',
    PercentFor45DayCancellation: '100',
    PercentFor31DayCancellation: '100'
  }
}
