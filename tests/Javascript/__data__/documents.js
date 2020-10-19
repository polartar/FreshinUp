export const FIXTURE_DOCUMENTS = [{
  uuid: 'f38af949-678f-42d3-8908-f2527306c1d8',
  title: 'aliasa',
  status_id: 2,
  type_id: 1,
  description: 'QZkBryOdhffHIouRmEBnrA7fhub6DHkvirpOdkO5IHTaqMRFKY',
  notes: 'XzU9ublRc0QGJ4l9J3eK',
  owner: {
    id: 7,
    uuid: '8c708253-1da5-4ab1-b149-bedb93e48fa8',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 6,
    level_name: 'Company Director',
    first_name: 'Level 6',
    last_name: 'User',
    name: 'Level 6 User',
    email: 'level6@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: false,
    joined_at: '2019-09-16 06:26:03',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: '0623e163-d229-4fe9-b54f-6bbfd5b559e0',
    name: 'eligendi',
    square_id: '46151'
  },
  template_uuid: 'a039ca2e-f23f-3719-907b-a678a0f61712',
  template: {
    uuid: 'a039ca2e-f23f-3719-907b-a678a0f61712',
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
The Parties have agreed to the menu attached to this Catering Agreement as Exhibit A. Caterer reserves the right to make small changes to the menu if key ingredients are unable to be sourced due to reasons beyond the control of the Parties. The following limitations will apply to this reservation of right - {{event.LimitationsOnMenuAlterations}}. No alcoholic beverages will be served
without a separate agreement relating thereto.
</p>
<h3>3. Coordination with Venue</h3>
<p>
Caterer will need to have access to the Venue no later than {{event.PrepAdvanceTime}} hours in advance of the Start Time for the Event, and {{event.CleanUpTime}} hours after the End Time for clean up. Client will make all necessary arrangements, at Client’s expense, to get this access arranged.
In exchange for the services of Caterer as specified in this Catering Contract, Client will pay to
Caterer $ {{event.PerPersonCharge}} per person attending the event, but in no event less than the
Guest Count provided by Client to Caterer one week in advance of the Event. As of the signing of
this Contract, the total amount is estimated to be $ {{event.EstimatedTotalCost}} (“Estimated Total
Cost”).
Payment will be made to the Caterer as follows: $ {{event.Deposit}} deposit due on the date of signing,
and the balance of approximately $ {{event.ApproximateSecondPayment}} will be due one week in
advance of the event. The exact amount due will be determined, and provided from Client to Caterer
in writing, one week in advance of the Event along with a Final Guest Count.
</p>
<h3>5. Responsibilities for Related Costs</h3>
<p>
Client is solely responsible for all costs and/or deposits relating to use of the Venue, and for obtaining
any necessary permissions, authorizations, or other requirement of Caterer providing services at the
Venue.
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
  `
  },
  assigned_type: 2,
  expiration_at: '2019-10-09 20:03:19',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:51.000000Z'
},
{
  uuid: 'acb7cd62-98fd-46b3-969f-f8c5f069d778',
  title: 'eius',
  status_id: 1,
  type_id: 2,
  description: 'GNWEGRNcWrnNd7Tep4vZ8QOIzmYMle9x65kyieoBvOLyAoi4Od',
  notes: 'X2bD5v8zskKlxgUq739q',
  owner: {
    id: 11,
    uuid: '2b03f5e2-8e1a-4e2a-8c0c-d17fb1f16ae3',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 2,
    level_name: 'Platform Director',
    first_name: 'Colleague 2',
    last_name: 'User',
    name: 'Colleague 2 User',
    email: 'colleague2@example.com',
    mobile_phone: '(522) 223-1234',
    office_phone: '(520) 256-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: true,
    joined_at: '2019-09-16 06:26:03',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: '68714355-cade-4cfe-9db5-3ec28c56ba07',
    name: 'facere',
    square_id: '97098'
  },
  assigned_type: 2,
  expiration_at: '2019-10-06 18:42:49',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: '17838ae4-5b52-4044-a966-527b60c09b1d',
  title: 'voluptas',
  status_id: 3,
  type_id: 1,
  description: 'nhQeRf3NINeP5YSRvuIGzpRvqhtjStNNNmkxi3vrW0L8MwwziF',
  notes: 'IqUH6F2SBVtoM8YZXW7p',
  owner: {
    id: 1,
    uuid: 'c0eec683-dba9-4338-8f11-2fdce17e3432',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 1,
    level_name: 'Platform Admin',
    first_name: 'Demo',
    last_name: 'Admin',
    name: 'Demo Admin',
    email: 'demoAdmin@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '2019-09-23 11:03:57',
    has_admin_access: true,
    joined_at: '2019-09-16 06:26:02',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: '668c62b4-6e4f-4840-b464-f00dabe1eefd',
    name: 'consequatur'
  },
  assigned_type: 4,
  expiration_at: '2019-10-02 21:12:24',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: '4a95eefe-008e-4254-92ca-e38cbf046805',
  title: 'est',
  status_id: 1,
  type_id: 2,
  description: 'dZNsqz0oStLr8gQhtSxDcldgjxD9ooOt2ki109giWQ7C1euPsO',
  notes: '58tkfqulvobZichqDedN',
  owner: {
    id: 2,
    uuid: '4acc2e97-1d4c-4e4a-961b-6e1d358e4456',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 1,
    level_name: 'Platform Admin',
    first_name: 'Level 1',
    last_name: 'User',
    name: 'Level 1 User',
    email: 'level1@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: true,
    joined_at: '2019-09-16 06:26:03',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: 'e1e7c79c-40e1-4b05-899a-c97d4069630d',
    name: 'eos',
    square_id: '84901'
  },
  assigned_type: 2,
  expiration_at: '2019-10-02 09:10:59',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: '0801264a-2b89-4497-9ec2-a5b21b7ac1be',
  title: 'quidem',
  status_id: 4,
  type_id: 1,
  description: '9vPgMQNb7z3ORCYJrHHYjyQpEYt9IDMl57KgaB2fqAH4AklrE4',
  notes: '1dn6T7ESmpxugGsCmfPo',
  owner: {
    id: 6,
    uuid: '001be769-cc70-4c52-8b1a-0bbc4bf6a093',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 5,
    level_name: 'Company Admin',
    first_name: 'Level 5',
    last_name: 'User',
    name: 'Level 5 User',
    email: 'level5@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: false,
    joined_at: '2019-09-16 06:26:03',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: 'e1e7c79c-40e1-4b05-899a-c97d4069630d',
    name: 'eos',
    square_id: '84901'
  },
  assigned_type: 2,
  expiration_at: '2019-10-07 16:30:38',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: 'f18bddd6-4f15-4607-bcf2-d2f3c1c76908',
  title: 'beatae',
  status_id: 2,
  type_id: 1,
  description: 'S1JSLox58mdt2VFPALaSY15qPbrhpcSeJtTMauUp3x5b6qju4D',
  notes: 'zA1FFlty12ekGy5C57uv',
  owner: {
    id: 17,
    uuid: '4f1236e6-1227-4cff-a353-19c144399ed2',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 8,
    level_name: 'Company Member',
    first_name: 'Colleague 8',
    last_name: 'User',
    name: 'Colleague 8 User',
    email: 'colleague8@example.com',
    mobile_phone: '(522) 223-1234',
    office_phone: '(520) 256-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: false,
    joined_at: '2019-09-16 06:26:04',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: '9b575bbe-30cb-4869-a0dd-92c9f9a5ff87',
    name: 'eum',
    square_id: '94672'
  },
  assigned_type: 2,
  expiration_at: '2019-10-08 19:33:07',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: '447ee532-0854-41ba-8b2d-757cd47edb2d',
  title: 'vel',
  status_id: 4,
  type_id: 1,
  description: 'RWFDSgZ6GQ73EMuE2PBdyrr4oNgbc1eimmYQcBRLatFSoQCLB6',
  notes: 'Plbdb3FfrTTERZ6e6M3l',
  owner: {
    id: 3,
    uuid: 'f80dd12c-98b2-4a20-891a-79e080d57f4b',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 2,
    level_name: 'Platform Director',
    first_name: 'Level 2',
    last_name: 'User',
    name: 'Level 2 User',
    email: 'level2@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: true,
    joined_at: '2019-09-16 06:26:03',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: '767fd40b-7f6b-40bf-a8a2-a9702c2fdf28',
    name: 'itaque'
  },
  assigned_type: 4,
  expiration_at: '2019-10-02 04:23:52',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: '9ddc0a0a-9e54-40e8-84db-6b9907b68ba4',
  title: 'est',
  status_id: 3,
  type_id: 1,
  description: 'y4EZ98cRZmVNXfyrqlIqrtMR4n5l63t0l2M5wcqixVvHuZmuBB',
  notes: 'nwv7Ay1tnjsbyn4kehhM',
  owner: {
    id: 14,
    uuid: '173813b6-4971-4bbe-80f7-b69c4b45f078',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 5,
    level_name: 'Company Admin',
    first_name: 'Colleague 5',
    last_name: 'User',
    name: 'Colleague 5 User',
    email: 'colleague5@example.com',
    mobile_phone: '(522) 223-1234',
    office_phone: '(520) 256-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: false,
    joined_at: '2019-09-16 06:26:04',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: '10846c51-e9c1-4fca-87e1-146ffda3bcb9',
    name: 'necessitatibus',
    square_id: '75246'
  },
  assigned_type: 2,
  expiration_at: '2019-10-09 23:20:03',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: 'cc080f0b-0fa0-48ad-83b5-ce505be3d357',
  title: 'sunt',
  status_id: 5,
  type_id: 1,
  description: '2cpuPfu8j02kN2Bjb4IifL3TTa3N1zHnUebL9TYJ3kzdoTC1jm',
  notes: 'jnzgjATf6GAYnNAEhANq',
  owner: {
    id: 17,
    uuid: '4f1236e6-1227-4cff-a353-19c144399ed2',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 8,
    level_name: 'Company Member',
    first_name: 'Colleague 8',
    last_name: 'User',
    name: 'Colleague 8 User',
    email: 'colleague8@example.com',
    mobile_phone: '(522) 223-1234',
    office_phone: '(520) 256-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: false,
    joined_at: '2019-09-16 06:26:04',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    uuid: 'ecdce631-b86c-4429-8907-3ea5d394c2d6',
    name: 'quo',
    square_id: '28682'
  },
  assigned_type: 2,
  expiration_at: '2019-10-06 08:04:07',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
},
{
  uuid: '020278c8-3144-4d21-b438-08a05bb5147f',
  title: 'est',
  status_id: 3,
  type_id: 2,
  description: 'bcvX0DulSFiqLRCaIFWc69ABIVjHgNspHKDz1SnbEUrE9EVsXE',
  notes: 'Sy0HbRbNIRMhVViWukyN',
  owner: {
    id: 1,
    uuid: 'c0eec683-dba9-4338-8f11-2fdce17e3432',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 1,
    level_name: 'Platform Admin',
    first_name: 'Demo',
    last_name: 'Admin',
    name: 'Demo Admin',
    email: 'demoAdmin@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '2019-09-23 11:03:57',
    has_admin_access: true,
    joined_at: '2019-09-16 06:26:02',
    industry_roles: [],
    addresses: []
  },
  assigned: {
    id: 6,
    uuid: '001be769-cc70-4c52-8b1a-0bbc4bf6a093',
    company_id: 1,
    company_name: 'Laravel',
    status: 1,
    type: 1,
    level: 5,
    level_name: 'Company Admin',
    first_name: 'Level 5',
    last_name: 'User',
    name: 'Level 5 User',
    email: 'level5@example.com',
    mobile_phone: '(520) 223-1234',
    office_phone: '(522) 456-7890',
    notes: null,
    title: null,
    avatar: 'https://via.placeholder.com/800x600.png',
    requested_company: null,
    company: {
      id: 1,
      uuid: '12d6c339-4825-482b-8f1a-c85a1dfddf5f',
      created_at: '2019-09-16 06:26:02',
      status: 1,
      name: 'Laravel',
      address: '9858 Test Ave',
      address2: 'unit 10',
      city: 'Chicago',
      state: 'IL',
      zip: '60606',
      country: 'US',
      website: null,
      notes: null,
      members_count: null,
      teams_count: null,
      tags: [],
      logo: 'https://via.placeholder.com/800x600.png',
      company_types: [{
        id: 9,
        name: 'Supplier',
        key_id: 'supplier'
      }]
    },
    last_login: '',
    has_admin_access: false,
    joined_at: '2019-09-16 06:26:03',
    industry_roles: [],
    addresses: []
  },
  assigned_type: 1,
  expiration_at: '2019-10-04 23:52:41',
  created_at: '2019-09-30T03:51:14.000000Z',
  updated_at: '2019-09-30T03:51:14.000000Z'
}]

export const FIXTURE_DOCUMENT = FIXTURE_DOCUMENTS[0]

export const FIXTURE_DOCUMENTS_RESPONSE = {
  data: FIXTURE_DOCUMENTS,
  links: {
    first: 'http://localhost:8081/api/foodfleet/documents?page%5Bsize%5D=10&page%5Bnumber%5D=1',
    last: 'http://localhost:8081/api/foodfleet/documents?page%5Bsize%5D=10&page%5Bnumber%5D=5',
    prev: null,
    next: 'http://localhost:8081/api/foodfleet/documents?page%5Bsize%5D=10&page%5Bnumber%5D=2'
  },
  meta: {
    current_page: 1,
    from: 1,
    last_page: 5,
    path: 'http://localhost:8081/api/foodfleet/documents',
    per_page: 10,
    to: 10,
    total: 50
  }
}
export const EMPTY_DOCUMENT = {
  assigned_type: 1,
  created_at: null,
  description: null,
  expiration_at: null,
  notes: null,
  status_id: 1,
  title_id: null,
  type_id: 1,
  updated_at: null,
  uuid: null
}
