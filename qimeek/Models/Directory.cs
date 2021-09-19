using System;
using System.Collections.Generic;

#nullable disable

namespace qimeek.Models
{
    public partial class Directory
    {
        public Directory()
        {
            Bookmarks = new HashSet<Bookmark>();
            InverseParent = new HashSet<Directory>();
        }

        public int Id { get; set; }
        public int? ParentId { get; set; }
        public string Name { get; set; }
        public DateTime DateAdded { get; set; }
        public string UserId { get; set; }

        public virtual Directory Parent { get; set; }
        public virtual AspNetUser User { get; set; }
        public virtual ICollection<Bookmark> Bookmarks { get; set; }
        public virtual ICollection<Directory> InverseParent { get; set; }
    }
}
