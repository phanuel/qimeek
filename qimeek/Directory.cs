using System;
using System.Collections.Generic;

#nullable disable

namespace qimeek
{
    public partial class Directory
    {
        public int Id { get; set; }
        public int? ParentId { get; set; }
        public string Name { get; set; }
        public DateTime DateAdded { get; set; }
        public string UserId { get; set; }
    }
}
