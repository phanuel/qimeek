using System;
using System.Collections.Generic;

#nullable disable

namespace qimeek
{
    public partial class Bookmark
    {
        public int Id { get; set; }
        public int DirectoryId { get; set; }
        public string Url { get; set; }
        public string Title { get; set; }
        public DateTime DateAdded { get; set; }
        public int UserId { get; set; }
    }
}
