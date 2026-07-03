import { useState, useEffect } from "react";
import { Heart, Download, Search } from "lucide-react";
import { storage, Donation } from "../utils/storage";

export function DonationsPage() {
  const [donations, setDonations] = useState<Donation[]>([]);
  const [searchQuery, setSearchQuery] = useState("");

  useEffect(() => {
    setDonations(storage.getDonations());
  }, []);


  const filteredDonations = donations.filter(
    (d) =>
      d.donor.toLowerCase().includes(searchQuery.toLowerCase()) ||
      d.email.toLowerCase().includes(searchQuery.toLowerCase()) ||
      d.phone.includes(searchQuery)
  );

  // Dynamic stats calculation
  const totalRaised = donations.reduce((sum, d) => sum + d.amount, 0);
  const totalDonors = new Set(donations.map((d) => d.email.toLowerCase())).size;
  const avgDonation = donations.length > 0 ? totalRaised / donations.length : 0;
  const thisMonthRaised = donations
    .filter((d) => {
      const donationMonth = new Date(d.date).getMonth();
      const currentMonth = new Date().getMonth();
      return donationMonth === currentMonth;
    })
    .reduce((sum, d) => sum + d.amount, 0);

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">Donations</h1>
          <p className="text-muted-foreground mt-1">
            Track and manage donations to your organization
          </p>
        </div>
      </div>

      {/* Stats Cards */}
      <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="flex items-center justify-between mb-2">
            <div className="text-sm text-muted-foreground">Total Raised</div>
            <Heart className="w-5 h-5 text-[#EF4444]" />
          </div>
          <div className="text-2xl font-semibold">
            ₹{totalRaised.toLocaleString("en-IN")}
          </div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Unique Donors</div>
          <div className="text-2xl font-semibold mt-1">{totalDonors}</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">This Month</div>
          <div className="text-2xl font-semibold mt-1">
            ₹{thisMonthRaised.toLocaleString("en-IN")}
          </div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Avg. Donation</div>
          <div className="text-2xl font-semibold mt-1">
            ₹{Math.round(avgDonation).toLocaleString("en-IN")}
          </div>
        </div>
      </div>

      {/* Main Table Card */}
      <div className="bg-card rounded-xl border border-border">
        <div className="p-6 border-b border-border">
          <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 className="font-semibold text-foreground">Recent Donations</h3>
            <div className="relative">
              <Search className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
              <input
                type="text"
                placeholder="Search by name, email, or phone..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-10 pr-4 py-2 w-full md:w-80 bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm"
              />
            </div>
          </div>
        </div>
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-accent border-b border-border">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Donor Name
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Email
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Phone No.
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Amount
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase">
                  Date
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border">
              {filteredDonations.length === 0 ? (
                <tr>
                  <td colSpan={6} className="px-6 py-12 text-center text-muted-foreground text-sm">
                    No donations found.
                  </td>
                </tr>
              ) : (
                filteredDonations.map((donation) => (
                  <tr key={donation.id} className="hover:bg-accent/50 transition-colors">
                    <td className="px-6 py-4 font-medium text-foreground">{donation.donor}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{donation.email}</td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{donation.phone}</td>
                    <td className="px-6 py-4 text-[#2E7D32] font-semibold">
                      ₹{donation.amount.toLocaleString("en-IN")}
                    </td>
                    <td className="px-6 py-4 text-sm text-muted-foreground">{donation.date}</td>
                  </tr>
                ))
              )}
            </tbody>
          </table>
        </div>
      </div>

    </div>
  );
}
