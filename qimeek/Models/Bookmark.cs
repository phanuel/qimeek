using System;
using System.Collections.Generic;

#nullable disable

namespace qimeek.Models
{
    public partial class Bookmark
    {
        public int Id { get; set; }
        public int DirectoryId { get; set; }
        public string Url { get; set; }
        public string Title { get; set; }
        public DateTime DateAdded { get; set; }
        public string UserId { get; set; }

        public virtual Directory Directory { get; set; }
        public virtual AspNetUser User { get; set; }
    }
}
