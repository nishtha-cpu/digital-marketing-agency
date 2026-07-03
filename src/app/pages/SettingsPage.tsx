import { Save, Upload, Key, Bell, Globe, Shield } from "lucide-react";

export function SettingsPage() {
  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">Settings</h1>
          <p className="text-muted-foreground mt-1">
            Manage your website settings and preferences
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors">
          <Save className="w-5 h-5" />
          Save All Changes
        </button>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
        {/* Sidebar */}
        <div className="lg:col-span-1">
          <div className="bg-card rounded-xl p-4 border border-border space-y-1">
            <button className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg bg-[#2E7D32] text-white">
              <Globe className="w-5 h-5" />
              <span>General</span>
            </button>
            <button className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-accent transition-colors text-left">
              <Shield className="w-5 h-5" />
              <span>Security</span>
            </button>
            <button className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-accent transition-colors text-left">
              <Bell className="w-5 h-5" />
              <span>Notifications</span>
            </button>
            <button className="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg hover:bg-accent transition-colors text-left">
              <Key className="w-5 h-5" />
              <span>API Keys</span>
            </button>
          </div>
        </div>

        {/* Content */}
        <div className="lg:col-span-3 space-y-6">
          {/* Organization Profile */}
          <div className="bg-card rounded-xl p-6 border border-border">
            <h3 className="font-semibold text-foreground mb-4">
              Organization Profile
            </h3>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-2">
                  Organization Name
                </label>
                <input
                  type="text"
                  defaultValue="PrayogBharti Foundation"
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Organization Logo
                </label>
                <div className="flex items-center gap-4">
                  <div className="w-20 h-20 rounded-lg bg-[#2E7D32] flex items-center justify-center text-white text-2xl font-semibold">
                    PB
                  </div>
                  <button className="flex items-center gap-2 px-4 py-2 border border-border rounded-lg hover:bg-accent transition-colors">
                    <Upload className="w-4 h-4" />
                    Upload Logo
                  </button>
                </div>
              </div>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label className="block text-sm font-medium mb-2">
                    Email
                  </label>
                  <input
                    type="email"
                    defaultValue="info@prayogbharti.org"
                    className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-2">
                    Phone
                  </label>
                  <input
                    type="tel"
                    defaultValue="+91 98765 43210"
                    className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                  />
                </div>
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Address
                </label>
                <textarea
                  rows={3}
                  defaultValue="New Delhi, India"
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
            </div>
          </div>

          {/* Social Media Links */}
          <div className="bg-card rounded-xl p-6 border border-border">
            <h3 className="font-semibold text-foreground mb-4">
              Social Media Links
            </h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium mb-2">
                  Facebook
                </label>
                <input
                  type="url"
                  placeholder="https://facebook.com/..."
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Twitter
                </label>
                <input
                  type="url"
                  placeholder="https://twitter.com/..."
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  LinkedIn
                </label>
                <input
                  type="url"
                  placeholder="https://linkedin.com/..."
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Instagram
                </label>
                <input
                  type="url"
                  placeholder="https://instagram.com/..."
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
            </div>
          </div>

          {/* SEO Settings */}
          <div className="bg-card rounded-xl p-6 border border-border">
            <h3 className="font-semibold text-foreground mb-4">
              SEO Settings
            </h3>
            <div className="space-y-4">
              <div>
                <label className="block text-sm font-medium mb-2">
                  Meta Title
                </label>
                <input
                  type="text"
                  defaultValue="PrayogBharti Foundation - Empowering Communities"
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Meta Description
                </label>
                <textarea
                  rows={3}
                  defaultValue="A nonprofit organization dedicated to education, research, and community development."
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
              <div>
                <label className="block text-sm font-medium mb-2">
                  Keywords
                </label>
                <input
                  type="text"
                  defaultValue="education, scholarship, research, NGO, community development"
                  className="w-full px-4 py-2 bg-input-background border border-border rounded-lg focus:border-primary focus:outline-none"
                />
              </div>
            </div>
          </div>

          {/* Email Settings */}
          <div className="bg-card rounded-xl p-6 border border-border">
            <h3 className="font-semibold text-foreground mb-4">
              Email Notifications
            </h3>
            <div className="space-y-3">
              {[
                "New contact form submissions",
                "New volunteer applications",
                "Donation notifications",
                "Newsletter subscriptions",
                "Event registrations",
              ].map((setting, index) => (
                <label
                  key={index}
                  className="flex items-center gap-3 p-3 rounded-lg hover:bg-accent cursor-pointer transition-colors"
                >
                  <input
                    type="checkbox"
                    defaultChecked
                    className="w-4 h-4 rounded border-border text-[#2E7D32] focus:ring-[#2E7D32]"
                  />
                  <span className="text-sm">{setting}</span>
                </label>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
