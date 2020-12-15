export const FIXTURE_USER_LEVELS = [
  {
    id: 1,
    enabled: 1,
    display_id: 1,
    default: 1,
    name: 'Super Admin',
    forCompany: false,
    forPlatform: true
  },
  {
    id: 2,
    enabled: 1,
    display_id: 2,
    default: 1,
    name: 'Manager',
    forCompany: false,
    forPlatform: true
  },
  {
    id: 5,
    enabled: 1,
    display_id: 5,
    default: 1,
    name: 'Company Owner',
    forCompany: true,
    forPlatform: false
  },
  {
    id: 8,
    enabled: 1,
    display_id: 8,
    default: 1,
    name: 'Company Employee',
    forCompany: true,
    forPlatform: false
  },
  {
    id: 10,
    enabled: 1,
    display_id: 1,
    default: 'Platform Admin',
    name: 'Platform Admin',
    forCompany: false,
    forPlatform: true
  },
  {
    id: 11,
    enabled: 1,
    display_id: 2,
    default: 'Platform Director',
    name: 'Platform Director',
    forCompany: false,
    forPlatform: true
  },
  {
    id: 14,
    enabled: 1,
    display_id: 5,
    default: 'Company Admin',
    name: 'Company Admin',
    forCompany: true,
    forPlatform: false
  },
  {
    id: 17,
    enabled: 1,
    display_id: 8,
    default: 'Company Member',
    name: 'Company Member',
    forCompany: true,
    forPlatform: false
  }
]
