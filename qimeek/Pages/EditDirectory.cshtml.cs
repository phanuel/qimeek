using System;
using System.Collections.Generic;
using System.Linq;
using System.Security.Claims;
using System.Threading.Tasks;
using Microsoft.AspNetCore.Mvc;
using Microsoft.AspNetCore.Mvc.RazorPages;
using Microsoft.EntityFrameworkCore;
using Microsoft.Extensions.Logging;
using qimeek.Data;
using qimeek.Models;

namespace qimeek.Pages
{
    public class EditDirectoryModel : PageModel
    {
        private readonly ILogger<DirectoriesModel> _logger;
        private readonly QimeekDbContext _dbContext;
        private readonly QimeekConfig _config;

        public EditDirectoryModel(ILogger<DirectoriesModel> logger, QimeekDbContext dbContext, QimeekConfig config)
        {
            _logger = logger;
            _dbContext = dbContext;
            _config = config;
        }

        [BindProperty]
        public Directory Directory { get; set; }

        public void OnGet(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Directory = _dbContext.Directories.Where(d => d.Id == id && d.UserId == userId).First();
        }

        public async Task<IActionResult> OnPostAsync(int id)
        {
            string userId = User.FindFirstValue(ClaimTypes.NameIdentifier);

            Directory directoryToUpdate = _dbContext.Directories.Where(d => d.Id == id && d.UserId == userId).First();

            if (directoryToUpdate != null)
            {
                if (await TryUpdateModelAsync<Directory>(
                    directoryToUpdate,
                    "directory",
                    d => d.Name))
                {
                    _dbContext.SaveChanges();
                }
            }
            
            return Redirect("~/Directories?id=" + directoryToUpdate.ParentId);
        }
    }
}
