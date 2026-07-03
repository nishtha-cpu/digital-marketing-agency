import { useState } from "react";
import {
  Plus,
  Search,
  Filter,
  Download,
  Edit,
  Trash2,
  Eye,
  MoreVertical,
} from "lucide-react";

const blogData = [
  {
    id: 1,
    title: "Empowering Communities Through Education",
    author: "Rajesh Kumar",
    category: "Education",
    status: "Published",
    date: "2026-06-20",
    views: 1234,
  },
  {
    id: 2,
    title: "Research and Innovation in Rural Areas",
    author: "Priya Sharma",
    category: "Research",
    status: "Published",
    date: "2026-06-18",
    views: 856,
  },
  {
    id: 3,
    title: "Mentorship Programs: Making a Difference",
    author: "Amit Patel",
    category: "Mentorship",
    status: "Draft",
    date: "2026-06-15",
    views: 423,
  },
  {
    id: 4,
    title: "Scholarship Opportunities for 2026",
    author: "Neha Gupta",
    category: "Scholarships",
    status: "Published",
    date: "2026-06-12",
    views: 2145,
  },
  {
    id: 5,
    title: "Community Development Success Stories",
    author: "Rajesh Kumar",
    category: "Community",
    status: "Published",
    date: "2026-06-10",
    views: 967,
  },
  {
    id: 6,
    title: "Technology and Social Impact",
    author: "Priya Sharma",
    category: "Technology",
    status: "Draft",
    date: "2026-06-08",
    views: 512,
  },
];

export function BlogsPage() {
  const [searchQuery, setSearchQuery] = useState("");
  const [selectedStatus, setSelectedStatus] = useState("all");

  const filteredBlogs = blogData.filter((blog) => {
    const matchesSearch =
      blog.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
      blog.author.toLowerCase().includes(searchQuery.toLowerCase());
    const matchesStatus =
      selectedStatus === "all" || blog.status.toLowerCase() === selectedStatus;
    return matchesSearch && matchesStatus;
  });

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex items-center justify-between">
        <div>
          <h1 className="text-3xl font-semibold text-foreground">
            Blog Management
          </h1>
          <p className="text-muted-foreground mt-1">
            Manage your blog posts and articles
          </p>
        </div>
        <button className="flex items-center gap-2 px-4 py-2.5 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors">
          <Plus className="w-5 h-5" />
          Add New Blog
        </button>
      </div>

      {/* Stats */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Total Blogs</div>
          <div className="text-2xl font-semibold mt-1">156</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Published</div>
          <div className="text-2xl font-semibold mt-1">142</div>
        </div>
        <div className="bg-card rounded-lg p-4 border border-border">
          <div className="text-sm text-muted-foreground">Drafts</div>
          <div className="text-2xl font-semibold mt-1">14</div>
        </div>
      </div>

      {/* Filters and Actions */}
      <div className="bg-card rounded-xl p-4 border border-border">
        <div className="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
          <div className="flex flex-col sm:flex-row gap-3 flex-1 w-full md:w-auto">
            {/* Search */}
            <div className="relative flex-1">
              <Search className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-muted-foreground" />
              <input
                type="text"
                placeholder="Search blogs..."
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
                className="pl-10 pr-4 py-2 w-full bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm"
              />
            </div>

            {/* Status Filter */}
            <select
              value={selectedStatus}
              onChange={(e) => setSelectedStatus(e.target.value)}
              className="px-4 py-2 bg-accent rounded-lg border border-transparent focus:border-primary focus:outline-none text-sm cursor-pointer"
            >
              <option value="all">All Status</option>
              <option value="published">Published</option>
              <option value="draft">Draft</option>
            </select>
          </div>

          <div className="flex gap-2">
            <button className="flex items-center gap-2 px-4 py-2 bg-accent rounded-lg hover:bg-accent/80 transition-colors text-sm">
              <Filter className="w-4 h-4" />
              Filters
            </button>
            <button className="flex items-center gap-2 px-4 py-2 bg-accent rounded-lg hover:bg-accent/80 transition-colors text-sm">
              <Download className="w-4 h-4" />
              Export
            </button>
          </div>
        </div>
      </div>

      {/* Table */}
      <div className="bg-card rounded-xl border border-border overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full">
            <thead className="bg-accent border-b border-border">
              <tr>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  <input type="checkbox" className="rounded" />
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  Title
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  Author
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  Category
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  Status
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  Date
                </th>
                <th className="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border">
              {filteredBlogs.map((blog) => (
                <tr key={blog.id} className="hover:bg-accent/50 transition-colors">
                  <td className="px-6 py-4">
                    <input type="checkbox" className="rounded" />
                  </td>
                  <td className="px-6 py-4">
                    <div className="font-medium text-foreground">
                      {blog.title}
                    </div>
                  </td>
                  <td className="px-6 py-4 text-sm text-muted-foreground">
                    {blog.author}
                  </td>
                  <td className="px-6 py-4">
                    <span className="px-2 py-1 text-xs rounded-full bg-[#E8F5E9] text-[#2E7D32]">
                      {blog.category}
                    </span>
                  </td>
                  <td className="px-6 py-4">
                    <span
                      className={`px-2 py-1 text-xs rounded-full ${
                        blog.status === "Published"
                          ? "bg-[#D1FAE5] text-[#059669]"
                          : "bg-[#FEF3C7] text-[#D97706]"
                      }`}
                    >
                      {blog.status}
                    </span>
                  </td>
                  <td className="px-6 py-4 text-sm text-muted-foreground">
                    {blog.date}
                  </td>
                  <td className="px-6 py-4">
                    <div className="flex items-center gap-2">
                      <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                        <Eye className="w-4 h-4 text-muted-foreground" />
                      </button>
                      <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                        <Edit className="w-4 h-4 text-muted-foreground" />
                      </button>
                      <button className="p-2 hover:bg-accent rounded-lg transition-colors">
                        <Trash2 className="w-4 h-4 text-[#EF4444]" />
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>

        {/* Pagination */}
        <div className="px-6 py-4 border-t border-border flex items-center justify-between">
          <div className="text-sm text-muted-foreground">
            Showing {filteredBlogs.length} of {blogData.length} blogs
          </div>
          <div className="flex gap-2">
            <button className="px-4 py-2 border border-border rounded-lg hover:bg-accent transition-colors text-sm">
              Previous
            </button>
            <button className="px-4 py-2 bg-[#2E7D32] text-white rounded-lg hover:bg-[#1B5E20] transition-colors text-sm">
              1
            </button>
            <button className="px-4 py-2 border border-border rounded-lg hover:bg-accent transition-colors text-sm">
              2
            </button>
            <button className="px-4 py-2 border border-border rounded-lg hover:bg-accent transition-colors text-sm">
              Next
            </button>
          </div>
        </div>
      </div>
    </div>
  );
}
