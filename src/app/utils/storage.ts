export interface Donation {
  id: string;
  donor: string;
  email: string;
  phone: string;
  amount: number;
  date: string;
}

export interface Volunteer {
  id: string;
  name: string;
  skills: string;
  email: string;
  aadhar: string;
  joined: string;
  status: "Pending" | "Approved" | "Rejected";
}

export interface ContactEnquiry {
  id: string;
  name: string;
  mail: string;
  enqueri: string;
  phone: string;
  date: string;
  status: "Read" | "Unread";
  starred: boolean;
}

export interface Subscriber {
  id: string;
  name: string;
  email: string;
  subscribed: string;
  status: "Active" | "Inactive";
}

const KEYS = {
  DONATIONS: "dashboard_donations",
  VOLUNTEERS: "dashboard_volunteers",
  CONTACTS: "dashboard_contacts",
  SUBSCRIBERS: "dashboard_subscribers",
};

export const storage = {
  getDonations(): Donation[] {
    const val = localStorage.getItem(KEYS.DONATIONS);
    return val ? JSON.parse(val) : [];
  },
  saveDonations(data: Donation[]) {
    localStorage.setItem(KEYS.DONATIONS, JSON.stringify(data));
  },

  getVolunteers(): Volunteer[] {
    const val = localStorage.getItem(KEYS.VOLUNTEERS);
    return val ? JSON.parse(val) : [];
  },
  saveVolunteers(data: Volunteer[]) {
    localStorage.setItem(KEYS.VOLUNTEERS, JSON.stringify(data));
  },

  getContacts(): ContactEnquiry[] {
    const val = localStorage.getItem(KEYS.CONTACTS);
    return val ? JSON.parse(val) : [];
  },
  saveContacts(data: ContactEnquiry[]) {
    localStorage.setItem(KEYS.CONTACTS, JSON.stringify(data));
  },

  getSubscribers(): Subscriber[] {
    const val = localStorage.getItem(KEYS.SUBSCRIBERS);
    return val ? JSON.parse(val) : [];
  },
  saveSubscribers(data: Subscriber[]) {
    localStorage.setItem(KEYS.SUBSCRIBERS, JSON.stringify(data));
  },
};
