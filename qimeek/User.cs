using System;
using System.Collections.Generic;

#nullable disable

namespace qimeek
{
    public partial class User
    {
        public int Id { get; set; }
        public string Login { get; set; }
        public string Password { get; set; }
        public string Email { get; set; }
        public DateTime? RegisterDate { get; set; }
        public DateTime? LastConnection { get; set; }
    }
}
